<?php

return [
    /* Hero featured post on /blog (must exist in articles) */
    'featured_slug' => 'essential-tips-restaurant-inventory-management',

    /*
    |--------------------------------------------------------------------------
    | Blog categories (Browse by Category on /blog)
    |--------------------------------------------------------------------------
    | Article counts are derived from the articles array below.
    */
    'categories' => [
        'pos-tips' => [
            'label' => 'POS Tips',
            'description' => 'Tips and tricks for getting the most out of your POS system',
            'breadcrumb' => 'POS Tips',
            'card_gradient' => 'from-orange-500 to-red-600',
            'icon' => 'pos', // used in blog.blade for SVG selection
        ],
        'inventory' => [
            'label' => 'Inventory',
            'description' => 'Best practices for restaurant inventory management',
            'breadcrumb' => 'Inventory',
            'card_gradient' => 'from-green-500 to-emerald-600',
            'icon' => 'inventory',
        ],
        'staff-management' => [
            'label' => 'Staff Management',
            'description' => 'Strategies for effective restaurant staff management',
            'breadcrumb' => 'Staff Management',
            'card_gradient' => 'from-blue-500 to-indigo-600',
            'icon' => 'staff',
        ],
        'analytics' => [
            'label' => 'Analytics',
            'description' => 'Using data to make better business decisions',
            'breadcrumb' => 'Analytics',
            'card_gradient' => 'from-purple-500 to-pink-600',
            'icon' => 'analytics',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Articles (slug => metadata). View: resources/views/pages/blog/{slug}.blade.php
    |--------------------------------------------------------------------------
    */
    'articles' => [
        // —— POS Tips (5)
        'mobile-pos-future-restaurant-operations' => [
            'category' => 'pos-tips',
            'title' => 'Mobile POS: The Future of Restaurant Operations',
            'excerpt' => 'Explore how mobile POS systems are revolutionizing restaurant operations and improving customer experience.',
            'date' => '2023-12-05',
            'read_minutes' => 6,
        ],
        'pos-custom-buttons-shortcuts-floor-speed' => [
            'category' => 'pos-tips',
            'title' => 'Custom Buttons and Shortcuts That Speed Up Floor Service',
            'excerpt' => 'Design your POS layout so common actions take one tap—fewer errors during rush and faster table turns.',
            'date' => '2024-02-10',
            'read_minutes' => 7,
        ],
        'pos-split-payments-refunds-audit-trail' => [
            'category' => 'pos-tips',
            'title' => 'Split Payments, Refunds, and a Clean Audit Trail',
            'excerpt' => 'Handle splits and refunds with roles, reasons, and receipts so every adjustment is explainable later.',
            'date' => '2024-02-18',
            'read_minutes' => 8,
        ],
        'pos-shift-close-discipline-cash-card' => [
            'category' => 'pos-tips',
            'title' => 'Shift Close Discipline: Cash, Card, and Tip Reconciliation',
            'excerpt' => 'A repeatable close process reduces variance and builds trust between managers and owners.',
            'date' => '2024-03-02',
            'read_minutes' => 7,
        ],
        'pos-device-security-updates-backups' => [
            'category' => 'pos-tips',
            'title' => 'POS Device Hygiene: Security, Updates, and Backups',
            'excerpt' => 'Keep terminals patched, roles tight, and recovery plans simple so outages never own your night.',
            'date' => '2024-03-14',
            'read_minutes' => 6,
        ],

        // —— Inventory (5)
        'essential-tips-restaurant-inventory-management' => [
            'category' => 'inventory',
            'title' => '10 Essential Tips for Restaurant Inventory Management',
            'excerpt' => 'Learn how to optimize inventory to reduce waste, control costs, and improve profitability.',
            'date' => '2023-12-20',
            'read_minutes' => 8,
        ],
        'inventory-par-levels-dynamic-reordering' => [
            'category' => 'inventory',
            'title' => 'Par Levels and Dynamic Reordering That Match Real Sales',
            'excerpt' => 'Set pars from usage patterns, not guesswork, and adjust when the menu or season shifts.',
            'date' => '2024-01-08',
            'read_minutes' => 7,
        ],
        'inventory-waste-tracking-spoilage-comps' => [
            'category' => 'inventory',
            'title' => 'Waste Tracking: Spoilage, Comps, and Training in One View',
            'excerpt' => 'Capture why product left the kitchen so you can coach the team and fix vendors or prep.',
            'date' => '2024-01-22',
            'read_minutes' => 7,
        ],
        'inventory-recipe-costing-menu-math' => [
            'category' => 'inventory',
            'title' => 'Recipe Costing and Menu Math That Protect Margin',
            'excerpt' => 'Tie every plate to ingredients and yield so price changes and specials stay profitable.',
            'date' => '2024-02-04',
            'read_minutes' => 8,
        ],
        'inventory-vendor-compliance-receiving' => [
            'category' => 'inventory',
            'title' => 'Vendor Compliance and Receiving Discipline',
            'excerpt' => 'Standard checks at the dock stop invoice surprises and keep your POS counts aligned with reality.',
            'date' => '2024-02-28',
            'read_minutes' => 7,
        ],

        // —— Staff Management (5)
        'staff-management-best-practices-restaurants' => [
            'category' => 'staff-management',
            'title' => 'Staff Management Best Practices for Restaurants',
            'excerpt' => 'Learn effective strategies for hiring, training, and retaining restaurant staff to build a strong team.',
            'date' => '2023-12-10',
            'read_minutes' => 6,
        ],
        'staff-scheduling-labor-percent-sales' => [
            'category' => 'staff-management',
            'title' => 'Scheduling to Labor Percent of Sales—Without Burning Out the Team',
            'excerpt' => 'Use forecasts and POS actuals to right-size shifts while keeping service standards intact.',
            'date' => '2024-01-15',
            'read_minutes' => 8,
        ],
        'staff-onboarding-trainers-checklists' => [
            'category' => 'staff-management',
            'title' => 'Onboarding That Sticks: Trainers, Checklists, and First-Week Wins',
            'excerpt' => 'Structure the first shifts so new hires can ring, run food, and handle voids with confidence.',
            'date' => '2024-02-01',
            'read_minutes' => 7,
        ],
        'staff-feedback-metrics-from-pos' => [
            'category' => 'staff-management',
            'title' => 'Coaching From POS Signals: Voids, Comps, and Ticket Times',
            'excerpt' => 'Turn transaction patterns into fair, specific feedback instead of vague “be faster” talks.',
            'date' => '2024-02-20',
            'read_minutes' => 7,
        ],
        'staff-retention-recognition-shift-culture' => [
            'category' => 'staff-management',
            'title' => 'Retention, Recognition, and Shift Culture on Busy Nights',
            'excerpt' => 'Small habits—clear roles, shout-outs, predictable breaks—reduce churn where it hurts most.',
            'date' => '2024-03-08',
            'read_minutes' => 6,
        ],

        // —— Analytics (5)
        'restaurant-sales-data-analytics' => [
            'category' => 'analytics',
            'title' => 'How to Increase Restaurant Sales with Data Analytics',
            'excerpt' => 'Discover how data analytics can help you identify trends, optimize menu pricing, and increase revenue.',
            'date' => '2023-12-15',
            'read_minutes' => 8,
        ],
        'analytics-menu-mix-engineering' => [
            'category' => 'analytics',
            'title' => 'Menu Mix and Engineering for Profit, Not Just Popularity',
            'excerpt' => 'Stars, puzzles, and dogs—use sales mix to promote what pays and fix what drags margin.',
            'date' => '2024-01-05',
            'read_minutes' => 8,
        ],
        'analytics-cashier-kpis-daily-weekly' => [
            'category' => 'analytics',
            'title' => 'Cashier and Service KPIs: Daily Pulse, Weekly Trends',
            'excerpt' => 'A short list of metrics your POS already knows—so you review what moves the needle.',
            'date' => '2024-01-28',
            'read_minutes' => 7,
        ],
        'analytics-customer-segments-visits' => [
            'category' => 'analytics',
            'title' => 'Customer Frequency and Segments You Can Actually Use',
            'excerpt' => 'From walk-ins to regulars, segment lightly and act on one campaign at a time.',
            'date' => '2024-02-12',
            'read_minutes' => 7,
        ],
        'analytics-seasonality-events-planning' => [
            'category' => 'analytics',
            'title' => 'Seasonality, Events, and Demand Planning From Historical POS Data',
            'excerpt' => 'Look back at last year’s curves to staff, prep, and promote without overstocking.',
            'date' => '2024-03-01',
            'read_minutes' => 8,
        ],
    ],
];
