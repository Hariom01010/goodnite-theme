<?php
declare(strict_types=1);

/**
 * Temporary visual catalogue for the standalone collection page.
 * The first four names/prices mirror the homepage implementation. The six
 * additional products and all filter attributes remain provisional until the
 * final catalogue feed is supplied.
 */

function goodnite_get_collections(): array {
	static $data = null;
	if ( null !== $data ) {
		return $data;
	}

	$products = [
		['id'=>'kayler','name'=>'Kayler','collection'=>'Dreamer Series','collection_slug'=>'dreamer','descriptor'=>'SupportCore Foam','price'=>42990,'image'=>'goodnite-product-01.webp','alt'=>'Studio image of the Kayler mattress','url'=>'/mattresses/kayler','comfort'=>'Medium Firm','material'=>'Foam','sizes'=>['Single','Double','Queen','King'],'featured_order'=>1,'sales_rank'=>2,'newest_order'=>8],
		['id'=>'dream-maker','name'=>'Dream Maker','collection'=>'Dreamer Series','collection_slug'=>'dreamer','descriptor'=>'Zoned Comfort','price'=>54990,'image'=>'goodnite-product-02.webp','alt'=>'Studio image of the Dream Maker mattress','url'=>'/mattresses/dream-maker','comfort'=>'Medium','material'=>'Pocket Spring','sizes'=>['Double','Queen','King'],'featured_order'=>2,'sales_rank'=>1,'newest_order'=>7],
		['id'=>'dream-catcher','name'=>'Dream Catcher','collection'=>'Love Series','collection_slug'=>'love','descriptor'=>'Memory Foam Relief','price'=>48990,'image'=>'goodnite-product-03.webp','alt'=>'Studio image of the Dream Catcher mattress','url'=>'/mattresses/dream-catcher','comfort'=>'Soft','material'=>'Memory Foam','sizes'=>['Single','Double','Queen','King'],'featured_order'=>3,'sales_rank'=>3,'newest_order'=>6],
		['id'=>'orion-care-lx','name'=>'Orion Care LX','collection'=>'Love Series','collection_slug'=>'love','descriptor'=>'IceSleep Surface','price'=>64990,'image'=>'goodnite-product-04.webp','alt'=>'Studio image of the Orion Care LX mattress','url'=>'/mattresses/orion-care-lx','comfort'=>'Medium Firm','material'=>'Pocket Spring','sizes'=>['Queen','King'],'featured_order'=>4,'sales_rank'=>4,'newest_order'=>5],
		['id'=>'celeste','name'=>'Celeste','collection'=>'Dreamer Series','collection_slug'=>'dreamer','descriptor'=>'Responsive Firm Support','price'=>38990,'image'=>'goodnite-product-05.png','alt'=>'Provisional studio image of the Celeste mattress','url'=>'/mattresses/celeste','comfort'=>'Firm','material'=>'Foam','sizes'=>['Single','Double','Queen'],'featured_order'=>5,'sales_rank'=>7,'newest_order'=>10],
		['id'=>'solace','name'=>'Solace','collection'=>'Dreamer Series','collection_slug'=>'dreamer','descriptor'=>'Natural Latex Comfort','price'=>57990,'image'=>'goodnite-product-06.png','alt'=>'Provisional studio image of the Solace mattress','url'=>'/mattresses/solace','comfort'=>'Medium','material'=>'Natural Latex','sizes'=>['Double','Queen','King'],'featured_order'=>6,'sales_rank'=>6,'newest_order'=>9],
		['id'=>'aurelia','name'=>'Aurelia','collection'=>'Love Series','collection_slug'=>'love','descriptor'=>'Pressure-Relief Memory Foam','price'=>59990,'image'=>'goodnite-product-07.png','alt'=>'Provisional studio image of the Aurelia mattress','url'=>'/mattresses/aurelia','comfort'=>'Medium','material'=>'Memory Foam','sizes'=>['Single','Double','Queen','King'],'featured_order'=>7,'sales_rank'=>5,'newest_order'=>4],
		['id'=>'equinox','name'=>'Equinox','collection'=>'Signature Series','collection_slug'=>'signature','descriptor'=>'Precision Pocket Support','price'=>74990,'image'=>'goodnite-product-08.png','alt'=>'Provisional studio image of the Equinox mattress','url'=>'/mattresses/equinox','comfort'=>'Firm','material'=>'Pocket Spring','sizes'=>['Queen','King'],'featured_order'=>8,'sales_rank'=>8,'newest_order'=>3],
		['id'=>'kingsley','name'=>'Kingsley','collection'=>'Signature Series','collection_slug'=>'signature','descriptor'=>'Latex Luxury Comfort','price'=>82990,'image'=>'goodnite-product-09.png','alt'=>'Provisional studio image of the Kingsley mattress','url'=>'/mattresses/kingsley','comfort'=>'Medium Firm','material'=>'Natural Latex','sizes'=>['Queen','King'],'featured_order'=>9,'sales_rank'=>9,'newest_order'=>2],
		['id'=>'serenity','name'=>'Serenity','collection'=>'Signature Series','collection_slug'=>'signature','descriptor'=>'Plush Hybrid Comfort','price'=>89990,'image'=>'goodnite-product-10.png','alt'=>'Provisional studio image of the Serenity mattress','url'=>'/mattresses/serenity','comfort'=>'Soft','material'=>'Hybrid','sizes'=>['Queen','King'],'featured_order'=>10,'sales_rank'=>10,'newest_order'=>1],
		['id'=>'kayler-plus','name'=>'Kayler Plus','collection'=>'Dreamer Series','collection_slug'=>'dreamer','descriptor'=>'SupportCore Foam','price'=>44990,'image'=>'goodnite-product-01.webp','alt'=>'Provisional duplicate studio image of the Kayler Plus mattress','url'=>'/mattresses/kayler-plus','comfort'=>'Medium Firm','material'=>'Foam','sizes'=>['Single','Double','Queen','King'],'featured_order'=>11,'sales_rank'=>11,'newest_order'=>11],
		['id'=>'dream-maker-plus','name'=>'Dream Maker Plus','collection'=>'Dreamer Series','collection_slug'=>'dreamer','descriptor'=>'Zoned Comfort','price'=>57990,'image'=>'goodnite-product-02.webp','alt'=>'Provisional duplicate studio image of the Dream Maker Plus mattress','url'=>'/mattresses/dream-maker-plus','comfort'=>'Medium','material'=>'Pocket Spring','sizes'=>['Double','Queen','King'],'featured_order'=>12,'sales_rank'=>12,'newest_order'=>12],
		['id'=>'dream-catcher-plus','name'=>'Dream Catcher Plus','collection'=>'Love Series','collection_slug'=>'love','descriptor'=>'Memory Foam Relief','price'=>51990,'image'=>'goodnite-product-03.webp','alt'=>'Provisional duplicate studio image of the Dream Catcher Plus mattress','url'=>'/mattresses/dream-catcher-plus','comfort'=>'Soft','material'=>'Memory Foam','sizes'=>['Single','Double','Queen','King'],'featured_order'=>13,'sales_rank'=>13,'newest_order'=>13],
		['id'=>'orion-care-plus','name'=>'Orion Care Plus','collection'=>'Love Series','collection_slug'=>'love','descriptor'=>'IceSleep Surface','price'=>67990,'image'=>'goodnite-product-04.webp','alt'=>'Provisional duplicate studio image of the Orion Care Plus mattress','url'=>'/mattresses/orion-care-plus','comfort'=>'Medium Firm','material'=>'Pocket Spring','sizes'=>['Queen','King'],'featured_order'=>14,'sales_rank'=>14,'newest_order'=>14],
		['id'=>'equinox-plus','name'=>'Equinox Plus','collection'=>'Signature Series','collection_slug'=>'signature','descriptor'=>'Precision Pocket Support','price'=>78990,'image'=>'goodnite-product-08.png','alt'=>'Provisional duplicate studio image of the Equinox Plus mattress','url'=>'/mattresses/equinox-plus','comfort'=>'Firm','material'=>'Pocket Spring','sizes'=>['Queen','King'],'featured_order'=>15,'sales_rank'=>15,'newest_order'=>15],
		['id'=>'serenity-plus','name'=>'Serenity Plus','collection'=>'Signature Series','collection_slug'=>'signature','descriptor'=>'Plush Hybrid Comfort','price'=>92990,'image'=>'goodnite-product-10.png','alt'=>'Provisional duplicate studio image of the Serenity Plus mattress','url'=>'/mattresses/serenity-plus','comfort'=>'Soft','material'=>'Hybrid','sizes'=>['Queen','King'],'featured_order'=>16,'sales_rank'=>16,'newest_order'=>16],
		['id'=>'kayler-air','name'=>'Kayler Air','collection'=>'Dreamer Series','collection_slug'=>'dreamer','descriptor'=>'SupportCore Foam','price'=>46990,'image'=>'goodnite-product-01.webp','alt'=>'Provisional duplicate studio image of the Kayler Air mattress','url'=>'/mattresses/kayler-air','comfort'=>'Medium Firm','material'=>'Foam','sizes'=>['Single','Double','Queen','King'],'featured_order'=>17,'sales_rank'=>17,'newest_order'=>17],
		['id'=>'dream-maker-air','name'=>'Dream Maker Air','collection'=>'Dreamer Series','collection_slug'=>'dreamer','descriptor'=>'Zoned Comfort','price'=>58990,'image'=>'goodnite-product-02.webp','alt'=>'Provisional duplicate studio image of the Dream Maker Air mattress','url'=>'/mattresses/dream-maker-air','comfort'=>'Medium','material'=>'Pocket Spring','sizes'=>['Double','Queen','King'],'featured_order'=>18,'sales_rank'=>18,'newest_order'=>18],
		['id'=>'orion-care-air','name'=>'Orion Care Air','collection'=>'Love Series','collection_slug'=>'love','descriptor'=>'IceSleep Surface','price'=>69990,'image'=>'goodnite-product-04.webp','alt'=>'Provisional duplicate studio image of the Orion Care Air mattress','url'=>'/mattresses/orion-care-air','comfort'=>'Medium Firm','material'=>'Pocket Spring','sizes'=>['Queen','King'],'featured_order'=>19,'sales_rank'=>19,'newest_order'=>19],
		['id'=>'serenity-air','name'=>'Serenity Air','collection'=>'Signature Series','collection_slug'=>'signature','descriptor'=>'Plush Hybrid Comfort','price'=>94990,'image'=>'goodnite-product-10.png','alt'=>'Provisional duplicate studio image of the Serenity Air mattress','url'=>'/mattresses/serenity-air','comfort'=>'Soft','material'=>'Hybrid','sizes'=>['Queen','King'],'featured_order'=>20,'sales_rank'=>20,'newest_order'=>20],
	];

	$shared_banners = [
		['id'=>'cooler-materials','after'=>4,'after_mobile'=>4,'title'=>'Breathable materials for cooler sleep.','image'=>'collection-banner-materials.png','alt'=>'Layered breathable mattress materials in a warm neutral studio'],
		['id'=>'considered-craft','after'=>8,'after_mobile'=>8,'title'=>'Considered craft in every detail.','image'=>'collection-banner-craft.png','alt'=>'Close detail of quilted mattress stitching and tailored upholstery'],
	];

	$data = [
		'all' => [
			'slug'=>'all','title'=>'The Goodnite Collection','description'=>'Ten thoughtfully engineered mattresses across three distinct collections—made for cooler nights, deeper support and comfort that feels personal.','hero_image'=>'collection-hero-desktop-v2.png','hero_mobile_image'=>'collection-hero-mobile-v2.png','hero_alt'=>'A calm premium bedroom featuring a beautifully dressed mattress','product_ids'=>array_column($products, 'id'),'products'=>$products,'banners'=>$shared_banners,
		],
		'dreamer' => ['slug'=>'dreamer','title'=>'Dreamer Series','description'=>'Dependable support, familiar comfort and purposeful materials for restorative everyday sleep.','hero_image'=>'goodnite-collection-dreamer.webp','hero_mobile_image'=>'goodnite-collection-dreamer.webp','hero_alt'=>'Light contemporary bedroom representing the Dreamer Series','product_ids'=>['kayler','dream-maker','celeste','solace'],'products'=>$products,'banners'=>$shared_banners],
		'love' => ['slug'=>'love','title'=>'Love Series','description'=>'Advanced sleep technology shaped around individual comfort and pressure relief.','hero_image'=>'goodnite-collection-love.webp','hero_mobile_image'=>'goodnite-collection-love.webp','hero_alt'=>'Refined modern bedroom representing the Love Series','product_ids'=>['dream-catcher','orion-care-lx','aurelia'],'products'=>$products,'banners'=>$shared_banners],
		'signature' => ['slug'=>'signature','title'=>'Signature Series','description'=>'Sophisticated constructions and premium materials for an elevated sleep experience.','hero_image'=>'goodnite-collection-signature.webp','hero_mobile_image'=>'goodnite-collection-signature.webp','hero_alt'=>'Richly detailed hotel-style bedroom representing the Signature Series','product_ids'=>['equinox','kingsley','serenity'],'products'=>$products,'banners'=>$shared_banners],
	];

	return $data;
}

return goodnite_get_collections();

