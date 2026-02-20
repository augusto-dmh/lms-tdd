# LMS (Learning Management System) with TDD

### Why this course was taken?
<ul>
<li>Learn Test-Driven Development (TDD) with Pest in a real Laravel application - understanding how to write tests first and let them guide the implementation.</li>
<li>Understand how to integrate payment processing (Paddle) into a Laravel app, including webhook handling, job dispatching, and the full purchase flow.</li>
<li>Get hands-on experience with Livewire components (video player, course interactions) and how to properly test them.</li>
<li>Learn how to work with third-party APIs (Twitter/X, Paddle Billing) in a testable way using fakes, mocks, and dependency injection.</li>
<li>Gain awareness of SEO practices in Laravel - social meta tags (Open Graph, Twitter Cards) and SEO testing with dedicated plugins.</li>
</ul>

### About the Application Built (including server-side)
<li>Learning Management System - users can browse courses, watch videos, track progress, and purchase courses</li>
<li>Session-based authentication from Jetstream</li>
<li>TALL Stack (Tailwind, Alpine, Laravel and Livewire)</li>
<li>Paddle Billing (v2) for payment processing via webhooks</li>
<li>Twitter/X API (v2) integration for auto-tweeting new courses</li>
<li>Dockerized with Laravel Sail</li>
<li>SQLite</li>
<li>Pest for testing with code coverage</li>

### Methodology
<ul>
<li>Reach myself a solution - with some <i>extra glow</i> or not - for the lesson based on the professor's solution - if any</li>
<li>Compare my approach to the professor's</li>
<li>Implement professor's solution</li>
<li>Repeat last steps with the next class</li>
</ul>

### Why this approach?

<p>While working we deal with projects involving multiple people.</p>
<p>Frequently i don't get solutions for the problems i face in a pass of magic (the most near to this is, by understanding the business rule around the feature, get a base-solution from LLMs). So i found better, after the initial configs have been set - e.g: how the routing is dealt -, to try my own approach in each class whenever possible - each class is a feature generally.</p>
<p>And besides that, why implement my own version based on previous versions of features based on <i>the professor's</i> version? Well, simlar reason: working with others implies developing based on previous code not always written by yourself, so developing based on the professor's approach intends to simulate the real-world scenario we pass daily in work.</p>

### What the "Extra Glow" Mean?

<p>My own solution can include additional aspects not asked by the professor. Below are a few examples from this project:</p>
<ul>
<li>Switched from Paddle Classic (v1, deprecated) to Paddle Billing (v2) on my own after noticing the course used a legacy version no longer available for new users - this required significant rework of the checkout flow, webhook handling, and tests.</li>
<li>Moved payment webhook processing logic into a dedicated Service class instead of handling it inside the Job, improving separation of concerns and testability through dependency injection.</li>
<li>Improved test naming to be more descriptive and behavior-oriented (e.g: <code>stores purchase for a buyer that is already a user</code> vs the instructor's generic <code>stores paddle purchase</code>).</li>
<li>Used Paddle's "custom data" mechanism to pass user_id through the transaction lifecycle instead of making an extra API call to resolve customer email - a real-world optimization discovered through manual testing with ngrok and a logging middleware.</li>
<li>Designed a two-prop title system for layouts (<code>page-title</code> for utility pages, <code>content-title</code> for SEO-relevant pages) instead of a single generic title prop (taht is, i show different page title based on the importance of the page in SEO matters).</li>
<li>Used Twitter API v2 and singleton pattern instead of v1 and binding as the instructor did.</li>
<li>Applied the principle that Livewire component methods should mutate state or react to state changes, not serve as simple boolean derivations that belong in templates (refactored <code>isCurrentVideo</code> out of VideoPlayer component).</li>
</ul>

### Why some commits are reverted and redone with the "teacher's version"?
<p>In the early stages of the course (displaying purchased courses, login/logout), some of my initial approaches were reverted in favor of the instructor's version. This happened because, at that point, the foundational patterns of the project were still being established (authentication setup with Jetstream, how purchased courses relate to users), and diverging too early from the instructor's base would have created friction in subsequent lessons that built upon those decisions. Once the foundation was solid, hybrid and fully custom versions became more frequent.</p>
