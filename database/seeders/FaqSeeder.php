<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Do you deliver across Zambia?',
                'answer' => 'Yes. We offer delivery across Zambia. Delivery timelines and any costs depend on your location and the items in your order—we will confirm this during checkout or when you enquire.',
                'sort_order' => 1,
            ],
            [
                'question' => 'How can I get help choosing a product?',
                'answer' => 'Message us on WhatsApp using the button on the website. Tell us what you’re looking for (watch / perfume / serum), your budget range, and preferences (style, scent profile, or skin routine goals). We’ll recommend options quickly.',
                'sort_order' => 2,
            ],
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept cash, bank transfer and other methods as agreed. For your convenience, we offer pay on delivery (COD) options where applicable. Exact payment options will be confirmed when you place an order or contact us.',
                'sort_order' => 3,
            ],
            [
                'question' => 'Do you offer warranty or support?',
                'answer' => 'Support depends on the product category. If you need help after your purchase, contact us and we’ll assist you based on the item and order details.',
                'sort_order' => 4,
            ],
            [
                'question' => 'How long does delivery take?',
                'answer' => 'Delivery times vary by product availability and your location. We’ll provide an estimated timeframe when you order or when you enquire.',
                'sort_order' => 5,
            ],
            [
                'question' => 'What products do you sell?',
                'answer' => 'We curate watches, perfumes, and skin care serums. Availability changes as we add new drops and restock popular items.',
                'sort_order' => 6,
            ],
            [
                'question' => 'Can I order via WhatsApp?',
                'answer' => 'Yes. You can chat with us on WhatsApp to confirm availability, ask questions, and place an order if needed.',
                'sort_order' => 7,
            ],
            [
                'question' => 'Do you offer gifting or curated bundles?',
                'answer' => 'Yes. We can help you choose gift-ready picks or create curated bundles (for example: perfume + serum, or a watch + fragrance). Message us and share the occasion and budget.',
                'sort_order' => 8,
            ],
            [
                'question' => 'How do I choose a perfume scent profile?',
                'answer' => 'Tell us the vibe you want (fresh, sweet, woody, spicy) and when you’ll wear it (daily, office, evening). We’ll recommend options that match.',
                'sort_order' => 9,
            ],
            [
                'question' => 'How do I check availability?',
                'answer' => 'Availability varies by product. You can browse our website for current offerings or contact us via WhatsApp to confirm stock for a specific item.',
                'sort_order' => 10,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                ['answer' => $faq['answer'], 'sort_order' => $faq['sort_order'], 'status' => true]
            );
        }
    }
}
