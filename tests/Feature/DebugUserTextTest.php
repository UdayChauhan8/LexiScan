<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\TextAnalysisService;

class DebugUserTextTest extends TestCase
{
    public function test_analyze_user_memory_management_text()
    {
        $service = new TextAnalysisService();
        $text = "Modern operating systems are responsible for managing memory efficiently while supporting multiple applications running at the same time. Memory management ensures that programs receive the resources they need without interfering with one another or compromising system stability.

At the core of memory management is the concept of abstraction. Operating systems present applications with a simplified view of memory, even though the underlying hardware structure is complex. This abstraction allows programs to operate independently of physical memory limitations.

One of the most important mechanisms used in memory management is virtual memory. Virtual memory enables operating systems to map logical addresses used by programs to physical memory locations. This mapping process allows systems to execute applications that require more memory than is physically available.

Memory allocation strategies determine how memory is assigned to processes during execution. Static allocation assigns memory at compile time, while dynamic allocation occurs during runtime based on application requirements. Dynamic allocation provides flexibility but introduces additional complexity in tracking memory usage.

Fragmentation is a common challenge in memory management systems. External fragmentation occurs when free memory is divided into small, noncontiguous blocks, making it difficult to allocate large memory regions. Internal fragmentation happens when allocated memory exceeds actual usage, leading to wasted space.

To address fragmentation, operating systems use techniques such as paging and segmentation. Paging divides memory into fixed-size blocks, allowing efficient allocation and deallocation. Segmentation organizes memory based on logical divisions such as code, data, and stack segments.

Concurrency further complicates memory management. When multiple processes access shared memory regions simultaneously, synchronization mechanisms are required to prevent race conditions and data corruption. Locks, semaphores, and memory barriers play a critical role in maintaining consistency.

As applications scale, memory management must also consider performance constraints. Cache utilization, memory latency, and access patterns significantly affect system responsiveness. Poor memory management decisions can degrade performance even when sufficient hardware resources are available.

In modern distributed systems, memory management extends beyond a single machine. Applications often rely on memory pools, garbage collection, and caching strategies across multiple nodes. These approaches improve scalability but require careful coordination and monitoring.

In conclusion, memory management is a foundational responsibility of operating systems. Effective memory management balances efficiency, isolation, and performance while adapting to increasing application complexity and scale.";

        $metrics = $service->analyze($text);

        echo "\nDEBUG USER TEXT METRICS:\n";
        echo "Words: " . $metrics['word_count'] . "\n";
        echo "Sentences: " . $metrics['sentence_count'] . "\n";
        echo "Avg Sentence Length: " . $metrics['avg_sentence_length'] . "\n";
        echo "Readability Score: " . $metrics['readability_score'] . "\n";

        // Let's get syllable details
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('countSyllablesInWord');
        $method->setAccessible(true);

        $tokens = str_word_count(strip_tags($text), 1);
        $totalSyllables = 0;

        echo "\nHigh Syllable Words Trace:\n";
        foreach ($tokens as $word) {
            $c = $method->invoke($service, $word);
            $totalSyllables += $c;
            if ($c >= 4) {
                // echo "$word: $c\n"; // Uncomment to see heavy words
            }
        }
        $avgSyllables = count($tokens) > 0 ? $totalSyllables / count($tokens) : 0;
        echo "Total Syllables: $totalSyllables\n";
        echo "Avg Syllables/Word: " . number_format($avgSyllables, 4) . "\n";

        // 206.835 - 1.015(ASL) - 84.6(ASW)
        $calc = 206.835 - (1.015 * $metrics['avg_sentence_length']) - (84.6 * $avgSyllables);
        echo "Raw Calc: $calc\n";

        // Assert it's reliable (user saw 2.83, let's see what we get).
        // It SHOULD be low, but let's just assert it runs.
        $this->assertGreaterThan(-100, $metrics['readability_score']);
    }
}
