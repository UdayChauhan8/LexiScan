import * as THREE from 'three';

/**
 * Antigravity Particle Animation (Three.js Port)
 * 
 * Ported from React Three Fiber code.
 * Implements a 3D particle system with "magnet" distortion, waves, and gentle orbit.
 */
export class AntigravityAnimation {
    constructor(canvasId, options = {}) {
        this.canvas = document.getElementById(canvasId);
        if (!this.canvas) {
            console.warn(`Antigravity: Canvas element #${canvasId} not found.`);
            return;
        }

        // Configuration
        this.config = Object.assign({
            count: 300,
            magnetRadius: 6,
            ringRadius: 7,
            waveSpeed: 0.4,
            waveAmplitude: 1,
            particleSize: 1.5,
            lerpSpeed: 0.05,
            color: '#5227FF',
            autoAnimate: true,
            particleVariance: 1,
            rotationSpeed: 0,
            depthFactor: 1,
            pulseSpeed: 3,
            particleShape: 'capsule',
            fieldStrength: 10,
        }, options);

        this.scene = new THREE.Scene();
        this.camera = new THREE.PerspectiveCamera(35, 1, 0.1, 1000);
        this.camera.position.z = 50;

        this.renderer = new THREE.WebGLRenderer({
            canvas: this.canvas,
            alpha: true,
            antialias: true
        });

        // State
        this.particlesData = [];
        this.dummy = new THREE.Object3D();
        this.lastMousePos = { x: 0, y: 0 };
        this.lastMouseMoveTime = 0;
        this.virtualMouse = { x: 0, y: 0 };

        // Time
        this.clock = new THREE.Clock(); // for delta time
        this.startTime = Date.now(); // for elapsed time in wave calc

        // Resize handling
        this.resizeObserver = new ResizeObserver(() => this.handleResize());
        this.resizeObserver.observe(this.canvas.parentElement || document.body);

        // Interaction
        window.addEventListener('mousemove', (e) => this.onMouseMove(e));

        this.init();
    }

    init() {
        this.handleResize();
        this.createMesh();
        this.animate();
    }

    handleResize() {
        const parent = this.canvas.parentElement;
        const rect = parent.getBoundingClientRect();

        this.renderer.setSize(rect.width, rect.height);
        this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2)); // optimize perfo

        this.camera.aspect = rect.width / rect.height;
        this.camera.updateProjectionMatrix();

        // Recalculate viewport dimensions at z=0 for logic mapping
        // Logic from R3F useThree().viewport
        // vFOV is in degrees
        const fov = this.camera.fov * (Math.PI / 180);
        const distance = this.camera.position.z;
        const height = 2 * Math.tan(fov / 2) * distance;
        const width = height * this.camera.aspect;

        this.viewport = { width, height };

        // Re-init particles if viewport changed significantly? 
        // For now, simpler to just update viewport ref in logic
        if (this.particlesData.length === 0) {
            this.initParticlesData();
        }
    }

    initParticlesData() {
        this.particlesData = [];
        const width = this.viewport.width || 100;
        const height = this.viewport.height || 100;

        for (let i = 0; i < this.config.count; i++) {
            const t = Math.random() * 100;
            const factor = 20 + Math.random() * 100;
            const speed = 0.01 + Math.random() / 200;
            const xFactor = -50 + Math.random() * 100;
            const yFactor = -50 + Math.random() * 100;
            const zFactor = -50 + Math.random() * 100;

            const x = (Math.random() - 0.5) * width;
            const y = (Math.random() - 0.5) * height;
            const z = (Math.random() - 0.5) * 20;

            const randomRadiusOffset = (Math.random() - 0.5) * 2;

            this.particlesData.push({
                t,
                factor,
                speed,
                xFactor,
                yFactor,
                zFactor,
                mx: x,
                my: y,
                mz: z,
                cx: x,
                cy: y,
                cz: z,
                vx: 0,
                vy: 0,
                vz: 0,
                randomRadiusOffset
            });
        }
    }

    createMesh() {
        let geometry;
        const s = this.config.particleShape;

        if (s === 'capsule') {
            geometry = new THREE.CapsuleGeometry(0.1, 0.4, 4, 8);
        } else if (s === 'sphere') {
            geometry = new THREE.SphereGeometry(0.2, 16, 16);
        } else if (s === 'box') {
            geometry = new THREE.BoxGeometry(0.3, 0.3, 0.3);
        } else {
            geometry = new THREE.TetrahedronGeometry(0.3);
        }

        const material = new THREE.MeshBasicMaterial({ color: this.config.color });
        this.mesh = new THREE.InstancedMesh(geometry, material, this.config.count);
        this.scene.add(this.mesh);

        // Initial update
        this.initParticlesData();
    }

    onMouseMove(event) {
        // Convert to Normalized Device Coordinates (-1 to +1)
        const rect = this.canvas.getBoundingClientRect();
        const x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
        const y = -((event.clientY - rect.top) / rect.height) * 2 + 1;

        const dist = Math.sqrt(Math.pow(x - this.lastMousePos.x, 2) + Math.pow(y - this.lastMousePos.y, 2));

        if (dist > 0.001) {
            this.lastMouseMoveTime = Date.now();
            this.lastMousePos = { x, y };
        }
    }

    animate() {
        requestAnimationFrame(() => this.animate());

        if (!this.mesh) return;

        const elapsedTime = this.clock.getElapsedTime();
        const v = this.viewport;

        // Mouse logic
        let destX = (this.lastMousePos.x * v.width) / 2;
        let destY = (this.lastMousePos.y * v.height) / 2;

        if (this.config.autoAnimate && Date.now() - this.lastMouseMoveTime > 2000) {
            destX = Math.sin(elapsedTime * 0.5) * (v.width / 4);
            destY = Math.cos(elapsedTime * 0.5 * 2) * (v.height / 4);
        }

        const smoothFactor = 0.05;
        this.virtualMouse.x += (destX - this.virtualMouse.x) * smoothFactor;
        this.virtualMouse.y += (destY - this.virtualMouse.y) * smoothFactor;

        const targetX = this.virtualMouse.x;
        const targetY = this.virtualMouse.y;

        const globalRotation = elapsedTime * this.config.rotationSpeed;

        // Particle Loop
        for (let i = 0; i < this.config.count; i++) {
            const particle = this.particlesData[i];

            // Logic ported from R3F
            particle.t += particle.speed / 2;
            const t = particle.t;

            const projectionFactor = 1 - particle.cz / 50;
            const projectedTargetX = targetX * projectionFactor;
            const projectedTargetY = targetY * projectionFactor;

            const dx = particle.mx - projectedTargetX;
            const dy = particle.my - projectedTargetY;
            const dist = Math.sqrt(dx * dx + dy * dy);

            let targetPos = { x: particle.mx, y: particle.my, z: particle.mz * this.config.depthFactor };

            // "Magnet" interaction
            if (dist < this.config.magnetRadius) {
                const angle = Math.atan2(dy, dx) + globalRotation;

                const wave = Math.sin(t * this.config.waveSpeed + angle) * (0.5 * this.config.waveAmplitude);
                const deviation = particle.randomRadiusOffset * (5 / (this.config.fieldStrength + 0.1));

                const currentRingRadius = this.config.ringRadius + wave + deviation;

                targetPos.x = projectedTargetX + currentRingRadius * Math.cos(angle);
                targetPos.y = projectedTargetY + currentRingRadius * Math.sin(angle);
                targetPos.z = particle.mz * this.config.depthFactor + Math.sin(t) * (1 * this.config.waveAmplitude * this.config.depthFactor);
            }

            // Lerp current pos to target
            particle.cx += (targetPos.x - particle.cx) * this.config.lerpSpeed;
            particle.cy += (targetPos.y - particle.cy) * this.config.lerpSpeed;
            particle.cz += (targetPos.z - particle.cz) * this.config.lerpSpeed;

            // Set matrix
            this.dummy.position.set(particle.cx, particle.cy, particle.cz);
            this.dummy.lookAt(projectedTargetX, projectedTargetY, particle.cz);
            this.dummy.rotateX(Math.PI / 2);

            // Scale
            const currentDistToMouse = Math.sqrt(
                Math.pow(particle.cx - projectedTargetX, 2) + Math.pow(particle.cy - projectedTargetY, 2)
            );

            const distFromRing = Math.abs(currentDistToMouse - this.config.ringRadius);
            let scaleFactor = 1 - distFromRing / 10;
            scaleFactor = Math.max(0, Math.min(1, scaleFactor));

            const finalScale = scaleFactor * (0.8 + Math.sin(t * this.config.pulseSpeed) * 0.2 * this.config.particleVariance) * this.config.particleSize;
            this.dummy.scale.set(finalScale, finalScale, finalScale);

            this.dummy.updateMatrix();
            this.mesh.setMatrixAt(i, this.dummy.matrix);
        }

        this.mesh.instanceMatrix.needsUpdate = true;
        this.renderer.render(this.scene, this.camera);
    }
}
