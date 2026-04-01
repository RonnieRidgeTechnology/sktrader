<?php

use App\Http\Controllers\InquiryController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use App\Models\Faq;
use App\Models\HomepageSection;
use App\Models\PageContent;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\VideoReel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Media: serve files from public/media (no /storage symlink)
|--------------------------------------------------------------------------
*/
Route::get('/media/{path}', [\App\Http\Controllers\MediaController::class, 'serve'])
    ->where('path', '.*')
    ->name('media.serve');

/*
|--------------------------------------------------------------------------
| SEO: sitemap & robots (no session/cookies)
|--------------------------------------------------------------------------
*/
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [RobotsController::class, 'index'])->name('robots');
Route::get('/llms.txt', [\App\Http\Controllers\LlmsTxtController::class, 'index'])->name('llms.txt');

/*
|--------------------------------------------------------------------------
| Public routes (SK Traders website)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $videoReels = collect();
    if (class_exists(VideoReel::class)) {
        $videoReels = VideoReel::where('status', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn ($r) => ['title' => $r->title, 'videoUrl' => $r->video_url]);
    }
    $testimonials = collect();
    if (class_exists(Testimonial::class)) {
        $testimonials = Testimonial::where('status', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(['quote', 'name', 'role', 'company']);
    }
    $hero = class_exists(HomepageSection::class)
        ? HomepageSection::where('key', 'hero')->first()
        : null;
    if ($hero) {
        $hero->refresh();
    }
    $heroData = $hero && is_array($hero->data) ? $hero->data : [];
    $normalizePath = function ($path) {
        if (empty($path) || ! is_string($path)) {
            return null;
        }
        $path = str_replace('\\', '/', trim($path));
        $path = preg_replace('#^/+(storage/|public/|media/)?#i', '', $path);
        return $path !== '' ? $path : null;
    };
    // Hero slider: background images = left + right + gallery. Use relative URLs so they work on any domain.
    $heroSliderImages = [];
    foreach (['image_left', 'image_right'] as $key) {
        $p = $normalizePath($heroData[$key] ?? null);
        if ($p) {
            $heroSliderImages[] = '/media/' . $p;
        }
    }
    foreach ($heroData['gallery_images'] ?? [] as $path) {
        $norm = $normalizePath($path);
        if ($norm) {
            $heroSliderImages[] = '/media/' . $norm;
        }
    }
    $heroGalleryImages = [];
    foreach ($heroData['gallery_images'] ?? [] as $path) {
        $norm = $normalizePath($path);
        if ($norm) {
            $heroGalleryImages[] = '/media/' . $norm;
        }
    }
    $sectionImageTrust = null;
    $sectionImageServices = null;
    $trustSection = class_exists(HomepageSection::class)
        ? HomepageSection::where('key', 'trust')->first()
        : null;
    if ($trustSection && ! empty($trustSection->data['section_image']) && Storage::disk('media')->exists($trustSection->data['section_image'])) {
        $sectionImageTrust = '/media/' . str_replace('\\', '/', trim($trustSection->data['section_image']));
    }
    $whatWeDoSection = class_exists(HomepageSection::class)
        ? HomepageSection::where('key', 'what_we_do')->first()
        : null;
    if ($whatWeDoSection && ! empty($whatWeDoSection->data['section_image']) && Storage::disk('media')->exists($whatWeDoSection->data['section_image'])) {
        $sectionImageServices = '/media/' . str_replace('\\', '/', trim($whatWeDoSection->data['section_image']));
    }
    $featuredProducts = collect();
    if (class_exists(Product::class)) {
        $featuredProducts = Product::with('category:id,name,slug,parent_id', 'category.parent:id,name')
            ->where('status', true)
            ->where('is_featured', true)
            ->orderBy('name')
            ->get(['id', 'category_id', 'name', 'slug', 'short_description', 'image', 'price'])
            ->map(function ($product) {
                $path = $product->image ? preg_replace('#^/?(storage/|public/|media/)?#', '', trim(str_replace('\\', '/', $product->image))) : null;
                $arr = $product->toArray();
                $arr['image_url'] = $path ? '/media/' . $path : null;
                return $arr;
            });
    }
    $homeSections = [];
    foreach (['spotlight', 'journey_strip', 'why_pillars', 'delivery_line', 'final_cta'] as $key) {
        $sec = class_exists(HomepageSection::class)
            ? HomepageSection::where('key', $key)->where('visible', true)->first()
            : null;
        if ($sec && is_array($sec->data)) {
            $homeSections[$key] = $sec->data;
            if ($key === 'spotlight' && ! empty($sec->data['image'])) {
                $path = preg_replace('#^/?(storage/|public/|media/)?#', '', trim(str_replace('\\', '/', $sec->data['image'])));
                if ($path !== '') {
                    $homeSections[$key]['image_url'] = '/media/' . $path;
                }
            }
        }
    }
    return Inertia::render('Home', [
        'title'                    => 'Home',
        'videoReels'               => $videoReels,
        'testimonials'             => $testimonials,
        'heroHeadline'             => $heroData['headline'] ?? 'Luxury essentials, curated.',
        'heroSubheading'           => $heroData['subheading'] ?? 'Watches, perfumes, and skin care serums — premium picks, delivered with care.',
        'heroSliderImages'         => $heroSliderImages,
        'heroGalleryImages'        => $heroGalleryImages,
        'sectionImageTrustUrl'     => $sectionImageTrust,
        'sectionImageServicesUrl' => $sectionImageServices,
        'featuredProducts'        => $featuredProducts,
        'homeSections'             => $homeSections,
    ]);
})->name('home');
Route::get('/about', fn () => Inertia::render('About', ['title' => 'About Us', 'pageContent' => PageContent::getForPage('about')]))->name('about');
Route::get('/services', fn () => Inertia::render('Services', ['title' => 'Our Services', 'pageContent' => PageContent::getForPage('services')]))->name('services');
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{productId}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove')->whereNumber('productId');

Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'show'])->name('checkout');
Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/thank-you/{order}', [\App\Http\Controllers\CheckoutController::class, 'thankYou'])->name('checkout.thank-you');
Route::get('/checkout/pay/{order}', [\App\Http\Controllers\CheckoutController::class, 'pay'])->name('checkout.pay');
Route::post('/checkout/initiate-payment', [\App\Http\Controllers\CheckoutController::class, 'initiatePayment'])->name('checkout.initiate-payment');
Route::get('/checkout/payment-status/{order}', [\App\Http\Controllers\CheckoutController::class, 'paymentStatus'])->name('checkout.payment-status');
Route::get('/checkout/paypal/start/{order}', [\App\Http\Controllers\CheckoutController::class, 'paypalStart'])->name('checkout.paypal.start');
Route::get('/checkout/paypal/return', [\App\Http\Controllers\CheckoutController::class, 'paypalReturn'])->name('checkout.paypal.return');
Route::get('/checkout/paypal/cancel/{order}', [\App\Http\Controllers\CheckoutController::class, 'paypalCancel'])->name('checkout.paypal.cancel');
Route::post('/zynlepay/webhook', [\App\Http\Controllers\ZynlePayWebhookController::class, 'handle'])->name('zynlepay.webhook');
Route::get('/private-label', fn () => Inertia::render('PrivateLabel', ['title' => 'Private Label', 'pageContent' => PageContent::getForPage('private-label')]))->name('private-label');
Route::get('/how-it-works', fn () => Inertia::render('HowItWorks', ['title' => 'How It Works', 'pageContent' => PageContent::getForPage('how-it-works')]))->name('how-it-works');
Route::get('/why-choose-us', fn () => Inertia::render('WhyChooseUs', ['title' => 'Why Choose Us', 'pageContent' => PageContent::getForPage('why-choose-us')]))->name('why-choose-us');
Route::get('/quality', fn () => Inertia::render('Quality', ['title' => 'Quality Control', 'pageContent' => PageContent::getForPage('quality')]))->name('quality');
Route::get('/faq', fn () => Inertia::render('Faq', [
    'title' => 'FAQ',
    'faqs'  => Faq::where('status', true)->orderBy('sort_order')->orderBy('id')->get(['question', 'answer']),
    'pageContent' => PageContent::getForPage('faq'),
]))->name('faq');
Route::get('/contact', fn () => Inertia::render('Contact', [
    'title' => 'Contact Us',
    'initialProductInterest' => request()->query('product_interest'),
    'pageContent' => PageContent::getForPage('contact'),
]))->name('contact');

Route::get('/privacy-policy', fn () => Inertia::render('PrivacyPolicy', ['title' => 'Privacy Policy', 'pageContent' => PageContent::getForPage('privacy-policy')]))->name('privacy-policy');
Route::get('/manufacturing-policy', fn () => Inertia::render('ManufacturingPolicy', ['title' => 'Manufacturing Policy', 'pageContent' => PageContent::getForPage('manufacturing-policy')]))->name('manufacturing-policy');
Route::get('/terms-and-conditions', fn () => Inertia::render('TermsAndConditions', ['title' => 'Terms & Conditions', 'pageContent' => PageContent::getForPage('terms-and-conditions')]))->name('terms-and-conditions');

Route::post('/inquiry', [InquiryController::class, 'store'])->name('inquiry.store')->middleware('throttle:5,1');
Route::get('/inquiry/thank-you', [InquiryController::class, 'thankYou'])->name('inquiry.thank-you');
Route::post('/newsletter', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe')->middleware('throttle:10,1');

/*
|--------------------------------------------------------------------------
| Auth – Account (store user) & Profile
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = request()->user();
    if ($user && $user->is_admin) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('account.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [\App\Http\Controllers\AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [\App\Http\Controllers\AccountController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\AccountController::class, 'showOrder'])->name('orders.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin (auth + is_admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/test-smtp', [\App\Http\Controllers\Admin\SettingsController::class, 'testSmtp'])->name('settings.test-smtp');
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('/inquiries', [\App\Http\Controllers\Admin\InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/export/csv', [\App\Http\Controllers\Admin\InquiryController::class, 'exportCsv'])->name('inquiries.export.csv');
    Route::get('/inquiries/{inquiry}', [\App\Http\Controllers\Admin\InquiryController::class, 'show'])->name('inquiries.show');
    Route::get('/subscribers', [\App\Http\Controllers\Admin\NewsletterSubscriberController::class, 'index'])->name('subscribers.index');
    Route::get('/subscribers/export/csv', [\App\Http\Controllers\Admin\NewsletterSubscriberController::class, 'exportCsv'])->name('subscribers.export.csv');
    Route::post('categories/reorder', [\App\Http\Controllers\Admin\CategoryController::class, 'reorder'])->name('categories.reorder');
    Route::get('categories/{category}/subcategories', [\App\Http\Controllers\Admin\CategoryController::class, 'subcategories'])->name('categories.subcategories');
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
    Route::patch('products/{product}/featured', [\App\Http\Controllers\Admin\ProductController::class, 'toggleFeatured'])->name('products.featured');
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)->except(['show']);
    Route::resource('faqs', \App\Http\Controllers\Admin\FaqController::class)->except(['show']);
    Route::resource('video-reels', \App\Http\Controllers\Admin\VideoReelController::class)->except(['show']);
    Route::resource('testimonials', \App\Http\Controllers\Admin\TestimonialController::class)->except(['show']);
    Route::get('/seo', [\App\Http\Controllers\Admin\SeoSettingController::class, 'index'])->name('seo.index');
    Route::get('/seo/{seo}/edit', [\App\Http\Controllers\Admin\SeoSettingController::class, 'edit'])->name('seo.edit');
    Route::put('/seo/{seo}', [\App\Http\Controllers\Admin\SeoSettingController::class, 'update'])->name('seo.update');
    Route::get('/homepage', [\App\Http\Controllers\Admin\HomepageSectionController::class, 'index'])->name('homepage.index');
    Route::get('/homepage/debug-hero', [\App\Http\Controllers\Admin\HomepageSectionController::class, 'debugHero'])->name('homepage.debug-hero');
    // Explicit spotlight edit so /admin/homepage/spotlight/edit always hits this
    Route::get('/homepage/spotlight/edit', fn () => app(\App\Http\Controllers\Admin\HomepageSectionController::class)->edit('spotlight'))->name('homepage.edit.spotlight');
    // Other sections by key
    Route::get('/homepage/{section}/edit', [\App\Http\Controllers\Admin\HomepageSectionController::class, 'edit'])->name('homepage.edit')->where('section', '[a-z0-9_]+');
    Route::get('/homepage/{id}/edit', function ($id) {
        if (is_numeric($id)) {
            $section = \App\Models\HomepageSection::find($id);
            if ($section) {
                return redirect()->route('admin.homepage.edit', $section->key);
            }
        }
        abort(404);
    })->where('id', '[0-9]+')->name('homepage.edit.legacy');
    Route::match(['put', 'post'], '/homepage/{section}', [\App\Http\Controllers\Admin\HomepageSectionController::class, 'update'])->name('homepage.update')->where('section', '[a-z0-9_]+');
    Route::get('/page-content', [\App\Http\Controllers\Admin\PageContentController::class, 'index'])->name('page-content.index');
    Route::get('/page-content/{pageKey}/edit', [\App\Http\Controllers\Admin\PageContentController::class, 'edit'])->name('page-content.edit');
    Route::match(['put', 'post'], '/page-content/{pageKey}', [\App\Http\Controllers\Admin\PageContentController::class, 'update'])->name('page-content.update');
});

require __DIR__.'/auth.php';
