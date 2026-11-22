<?php
declare(strict_types=1);

/** Kayler preview data. April 2022 Posture Comfort prices are NOT Kayler retail prices. */
$rows = [
    [72, 30, 26599, 28099], [72, 36, 31999, 33799], [72, 42, 37299, 39399],
    [72, 48, 42599, 44999], [72, 60, 53299, 56299], [72, 66, 58599, 61799],
    [72, 72, 63899, 67599], [75, 30, 27699, 29299], [75, 36, 33299, 35099],
    [75, 42, 38799, 40999], [75, 48, 44499, 46899], [75, 60, 55499, 58599],
    [75, 66, 61099, 64399], [75, 72, 66599, 70299], [78, 30, 28799, 30499],
    [78, 36, 34699, 36499], [78, 42, 40499, 42599], [78, 48, 46099, 48699],
    [78, 60, 57799, 60799], [78, 66, 63499, 67099], [78, 72, 69199, 73199],
    [80, 60, 59199, 62499], [80, 72, 71099, 74899], [84, 36, 37299, 39399],
    [84, 60, 62099, 65599], [84, 72, 74599, 78699],
];
$categories = [30 => 'Narrow Single', 36 => 'Single', 42 => 'Super Single',
    48 => 'Double', 60 => 'Queen', 66 => 'Queen Plus', 72 => 'King'];
$variants = [];
foreach ($rows as [$length, $width, $plain, $pillow]) {
    $id = $length . 'x' . $width;
    $variants[$id] = [
        'id' => $id, 'length_in' => $length, 'width_in' => $width,
        'category' => $categories[$width],
        'reference_without_pt_inr' => $plain,
        'reference_with_pt_inr' => $pillow,
    ];
}

return [
    'id' => 'kayler',
    'name' => 'Kayler',
    'collection' => 'Dreamer Collection',
    'collection_url' => '/mattresses/dreamer',
    'intro' => 'Everyday comfort, thoughtfully engineered.',
    'feature_pills' => [
        ['icon' => 'PDP Icons/Core Support.png', 'preset' => 'core-support', 'label' => 'Core Support'],
        ['icon' => 'PDP Icons/Natural Freshness.png', 'preset' => 'natural-freshness', 'label' => 'Natural Freshness'],
        ['icon' => 'PDP Icons/Quilted Finish.png', 'preset' => 'quilted-finish', 'label' => 'Quilted Finish'],
        ['icon' => 'PDP Icons/Responsive Comfort.png', 'preset' => 'responsive-comfort', 'label' => 'Responsive Comfort'],
    ],
    'comfort' => [
        'labels' => ['Extra Firm', 'Firm', 'Medium', 'Plush', 'Extra Plush'],
        'position' => 3,
        'notes' => '',
        'verified' => false,
    ],
    'gallery' => [
        ['src' => 'images/kayler/Frame 1.png', 'alt' => 'Kayler mattress product image, view 1', 'label' => 'Full mattress view', 'approved' => false],
        ['src' => 'images/kayler/Frame 2.png', 'alt' => 'Kayler mattress product image, view 2', 'label' => 'Side profile view', 'approved' => false],
        ['src' => 'images/kayler/Frame 3.png', 'alt' => 'Kayler mattress product image, view 3', 'label' => 'Top detail view', 'approved' => false],
        ['src' => 'images/kayler/Frame 4.png', 'alt' => 'Kayler mattress product image, view 4', 'label' => 'Additional mattress view', 'approved' => false],
        ['src' => 'images/kayler/Frame 5.png', 'alt' => 'Kayler mattress product image, view 5', 'label' => 'Additional mattress detail', 'approved' => false],
    ],
    'variant_prices_approved' => false,
    'approved_prices_inr' => [],
    'cart_connected' => false,
    'reference_source' => 'April 2022 Posture Comfort price list; applicability to Kayler unverified',
    'variants' => $variants,
    'default_variant' => '72x30',
    'custom' => [
        'rate_without_pt' => 12.55,
        'rate_with_pt' => 13.26,
        'preview_max_dimension_in' => 240,
        'min_length_in' => null,
        'max_length_in' => null,
        'min_width_in' => null,
        'max_width_in' => null,
    ],
    'pillow_top_explanation' => null,
    'construction' => [
        'approved' => false,
        'title' => "What's Inside the Mattress?",
        'image' => 'images/What inside Mattress.png',
        'layers' => [
            ['step' => '1.', 'title' => 'Bamboo Charcoal Fabric', 'description' => 'Helps manage moisture and odour while promoting breathable comfort.'],
            ['step' => '2.', 'title' => 'CloudFlex™ HR Soft Foam', 'description' => 'Provides responsive cushioning for a soft, comfortable first feel.'],
            ['step' => '3.', 'title' => 'SupportCore™ HR Foam', 'description' => 'Provides resilient underlying support and helps balance the comfort layer.'],
            ['step' => '4.', 'title' => 'Quilted Base Fabric', 'description' => 'Completes the mattress construction with a finished base layer.'],
        ],
    ],
    'tech_section_title' => 'The Details Make the Difference.',
    'technologies' => [
        [
            'name' => 'Bamboo Charcoal',
            'tagline' => 'Naturally fresh, breathable comfort.',
            'video' => 'Videos/Bamboo_charcoal_fabric_product_.mp4',
            'poster' => 'images/goodnite-technology-fabric-v2.png',
            'has_tm' => false,
        ],
        [
            'name' => 'CloudFlex',
            'tagline' => 'Soft cushioning that adapts and rebounds.',
            'video' => 'Videos/Mattress_animation_showing_foam.mp4',
            'poster' => '',
            'has_tm' => true,
        ],
        [
            'name' => 'SupportCore',
            'tagline' => 'Resilient support for lasting comfort.',
            'video' => 'Videos/Woman_lying_on_supportive_mattress.mp4',
            'poster' => '',
            'has_tm' => true,
        ],
    ],
    'specs' => [
        'materials' => '<p><strong>Mattress Material:</strong></p><ul><li>SmartGRID Technology</li><li>Ortho Relief Foam</li><li>7 Zone Body Support</li><li>Premium Melange Fabric with anti-skid base</li></ul><p><strong>Cover Material:</strong></p><ul><li>Premium Melange Fabric with SnowTec® fabric technology</li></ul>',
        'care' => '<ul><li>To clean stains and spills, use mild detergents and gently dab the cover instead of rubbing it.</li><li>Use a mattress protector to safeguard your SmartGRID mattress.</li><li>Refrain from using an iron on the mattress.</li><li>Avoid folding during moving; ensure that the mattress is not placed on a crib or frame with slats or bars spaced more than 2 inches apart.</li></ul>',
        'shipping' => '<p>Made-to-order mattresses are dispatched after quality checks. Delivery timelines are confirmed at checkout, and eligible returns are accepted according to Goodnite’s return policy.</p>',
    ],
    'faqs' => [
        ['question' => 'What comfort feel is Kayler?', 'answer' => 'Kayler is designed with a Plush comfort feel: welcoming on top with balanced support underneath.'],
        ['question' => 'Can I order a custom size?', 'answer' => 'Yes. Use the Custom Size calculator to enter your dimensions and view an approximate estimate. Final feasibility and pricing are confirmed by Goodnite.'],
        ['question' => 'Does Kayler come with a Pillow Top option?', 'answer' => 'Yes. The size selector lets you compare the available price for Without Pillow Top and With Pillow Top.'],
        ['question' => 'How should I care for my mattress?', 'answer' => 'Use a mattress protector, keep the surface dry and rotate the mattress periodically. Avoid folding or bending it.'],
        ['question' => 'How can I get help before ordering?', 'answer' => 'Our support team can help with comfort, sizing and custom requirements through the contact options above.'],
    ],
    'recommendations' => [
        ['name' => 'Dream Maker', 'collection' => 'Dreamer Collection', 'url' => '/mattresses/dream-maker', 'image' => 'goodnite-product-02.webp', 'alt' => 'Studio shot of the Dream Maker mattress'],
        ['name' => 'Dream Catcher', 'collection' => 'Love Collection', 'url' => '/mattresses/dream-catcher', 'image' => 'goodnite-product-03.webp', 'alt' => 'Studio shot of the Dream Catcher mattress'],
        ['name' => 'Kingsley', 'collection' => 'Signature Collection', 'url' => '/mattresses/kingsley', 'image' => 'goodnite-product-04.webp', 'alt' => 'Studio shot of the Kingsley mattress'],
    ],
];
