<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## About LexiScan

LexiScan is a powerful readability analysis tool built on Laravel. It helps writers optimize their content for specific audiences by calculating the Flesch Reading Ease score and providing content-aware insights.

## How to Interpret Scores

Unlike simple "higher is better" metrics, the ideal Flesch Reading Ease score depends entirely on your target audience and content type. LexiScan emphasizes **context** over raw numbers.

### Ideal Score Ranges

| Content Type | Ideal Range | Why? |
| :--- | :--- | :--- |
| **Child / Beginner** | **80 - 95** | Requires very short sentences and basic vocabulary for early readers. |
| **Lifestyle Blog** | **65 - 75** | conversational tone for general leisure reading. |
| **Marketing / SEO** | **55 - 65** | Balances professional credibility with persuasion. Scores > 80 may feel too simple. |
| **Business** | **45 - 60** | Precision and professional terminology are prioritized over simplicity. |
| **Technical** | **30 - 50** | Complex concepts often require longer sentences and technical jargon. |

> **Note:** A score of 45 is "bad" for a children's book but "excellent" for a medical journal. Use the **Score Context** block in your analysis report to judge your content accurately.

### Higher Is Not Always Better
Scores above 80 are rare for adult-focused writing and are not required for clarity. Chasing a score of 90+ for a business report will likely result in unnatural, over-simplified text that lacks authority.

---

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- [Multiple back-ends for session and cache storage](https://laravel.com/docs/session).
- [Expressive, intuitive database ORM](https://laravel.com/docs/eloquent).
- [Database agnostic schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.
