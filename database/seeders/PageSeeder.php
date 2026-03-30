<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title'      => 'Privacy Policy',
                'slug'       => 'privacy-policy',
                'status'     => 'active',
                'sort_order' => 1,
                'content'    => <<<'HTML'
<h2>Privacy Policy</h2>

<p>This Privacy Policy describes how DressRental Nepal ("we", "us", or "our") collects, uses, and shares information about you when you use our services.</p>

<h3>Information We Collect</h3>
<ul>
    <li><strong>Account Information:</strong> Name, email address, and phone number when you register.</li>
    <li><strong>Booking Information:</strong> Dress selections, booking dates, and rental history.</li>
    <li><strong>Payment Information:</strong> Transaction details processed through eSewa and Khalti. We do not store your full payment credentials.</li>
    <li><strong>Usage Data:</strong> Pages visited, search queries, and interaction with the platform.</li>
</ul>

<h3>How We Use Your Information</h3>
<ul>
    <li>To process bookings and payments.</li>
    <li>To communicate booking confirmations, reminders, and updates.</li>
    <li>To improve our platform and user experience.</li>
    <li>To comply with legal obligations.</li>
</ul>

<h3>Information Sharing</h3>
<p>We do not sell your personal information. We may share your data with:</p>
<ul>
    <li>Payment processors (eSewa, Khalti) to complete transactions.</li>
    <li>Service providers who assist in operating our platform.</li>
    <li>Law enforcement when required by applicable law.</li>
</ul>

<h3>Data Security</h3>
<p>We implement industry-standard security measures to protect your information. However, no method of transmission over the internet is 100% secure.</p>

<h3>Your Rights</h3>
<p>You have the right to access, correct, or delete your personal data. Please contact us to exercise these rights.</p>

<h3>Contact Us</h3>
<p>For privacy-related questions, please contact us using the details on our website.</p>
HTML,
            ],
            [
                'title'      => 'Terms and Conditions',
                'slug'       => 'terms-and-conditions',
                'status'     => 'active',
                'sort_order' => 2,
                'content'    => <<<'HTML'
<h2>Terms and Conditions</h2>

<p>Welcome to DressRental Nepal. By accessing or using our services, you agree to be bound by these Terms and Conditions. Please read them carefully.</p>

<h3>1. Rental Agreement</h3>
<ul>
    <li>Dresses are rented for a specified period as agreed at the time of booking.</li>
    <li>The renter is responsible for the dress during the rental period.</li>
    <li>Dresses must be returned in the same condition as received, clean and undamaged.</li>
</ul>

<h3>2. Payments</h3>
<ul>
    <li>An advance payment is required at the time of booking as specified.</li>
    <li>The remaining balance must be paid before or on the pickup date.</li>
    <li>Deposits are refunded upon return of the dress in good condition.</li>
</ul>

<h3>3. Cancellation Policy</h3>
<ul>
    <li>Cancellations made at least 48 hours before the booking date may receive a partial refund.</li>
    <li>Cancellations within 48 hours of the booking date are non-refundable.</li>
    <li>We reserve the right to cancel bookings due to unforeseen circumstances and will provide a full refund in such cases.</li>
</ul>

<h3>4. Damage and Loss</h3>
<ul>
    <li>The renter is liable for any damage, stains, or loss of the dress during the rental period.</li>
    <li>Damage charges will be assessed based on the extent of damage and the cost of repair or replacement.</li>
    <li>Any fine amount will be deducted from the deposit or charged separately.</li>
</ul>

<h3>5. User Responsibilities</h3>
<ul>
    <li>You must provide accurate and complete information during registration and booking.</li>
    <li>You must be at least 18 years old or have parental consent to use our services.</li>
    <li>You are responsible for keeping your account credentials secure.</li>
</ul>

<h3>6. Limitation of Liability</h3>
<p>DressRental Nepal is not liable for any indirect, incidental, or consequential damages arising from the use of our services.</p>

<h3>7. Changes to Terms</h3>
<p>We reserve the right to update these Terms and Conditions at any time. Continued use of our services after changes constitutes acceptance of the new terms.</p>

<h3>Contact Us</h3>
<p>For questions about these terms, please contact us using the details on our website.</p>
HTML,
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], $page);
        }
    }
}
