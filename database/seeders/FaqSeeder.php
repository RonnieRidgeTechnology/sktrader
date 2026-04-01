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
                'answer' => 'Yes. We offer nationwide shipping across Zambia. Whether you are in Lusaka, Ndola, Kitwe, Livingstone or elsewhere, we can deliver your furniture safely. Delivery terms and any costs depend on your location and order size—we will confirm this when you order or enquire.',
                'sort_order' => 1,
            ],
            [
                'question' => 'Can I visit your showroom in Lusaka?',
                'answer' => 'Yes. Our showroom in Lusaka is open for you to view sofas, garden furniture and other pieces in person. You can try before you buy and get advice from our team. We recommend calling or messaging ahead to confirm opening hours.',
                'sort_order' => 2,
            ],
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept cash, bank transfer and other methods as agreed. For your convenience, we offer pay on delivery (COD) options where applicable. Exact payment options will be confirmed when you place an order or contact us.',
                'sort_order' => 3,
            ],
            [
                'question' => 'Do you offer warranty on your furniture?',
                'answer' => 'Yes. We stand by our quality with warranty on our furniture as specified per product. If you need help after your purchase, our after-sales support is here to assist you.',
                'sort_order' => 4,
            ],
            [
                'question' => 'How long does delivery take?',
                'answer' => 'Delivery times vary by product availability and your location. In Lusaka, delivery is typically within a few days to a week. For other parts of Zambia, it may take a bit longer. We will give you an estimated timeframe when you order.',
                'sort_order' => 5,
            ],
            [
                'question' => 'What types of furniture do you sell?',
                'answer' => 'We sell a wide range of furniture including sofas and living room furniture, rattan and garden furniture (UV-resistant outdoor sets, corner sofas, chairs, tables), and other quality pieces for home and garden. From classic to modern styles—there is something for every space.',
                'sort_order' => 6,
            ],
            [
                'question' => 'How can I arrange a viewing or collection?',
                'answer' => 'You can message us on WhatsApp to arrange a viewing at our showroom or to organise collection. Use the WhatsApp button on our website or product pages—we are happy to help you find the right furniture and arrange a convenient time.',
                'sort_order' => 7,
            ],
            [
                'question' => 'Can I order custom or made-to-order furniture?',
                'answer' => 'We offer selected custom and made-to-order options. Contact us with your requirements—fabric, size, style—and we will let you know what is possible and provide a quote.',
                'sort_order' => 8,
            ],
            [
                'question' => 'How do I care for rattan or outdoor garden furniture?',
                'answer' => 'Our UV-resistant rattan and outdoor furniture is built for durability. For best results, keep pieces clean with a soft cloth and mild soap, and avoid leaving them in standing water. We can provide care guidelines with your purchase.',
                'sort_order' => 9,
            ],
            [
                'question' => 'Do you have furniture in stock, and how do I check availability?',
                'answer' => 'Availability varies by product. You can browse our website for current offerings, visit our Lusaka showroom to see and try pieces, or contact us via WhatsApp or phone to check stock for specific items. We will confirm availability when you enquire or order.',
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
