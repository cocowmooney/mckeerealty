<?php
$page_title = 'Contact Us';
$page_desc = 'Get in touch with McKee Realty in Flowood, MS. Call, email, or visit our office.';
require_once 'includes/header.php';

$form_sent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if ($name && $email && $message) {
        $ins = $db->prepare("INSERT INTO contacts (name, email, phone, message) VALUES (?, ?, ?, ?)");
        $ins->execute([$name, $email, $phone, $message]);
        
        mail('mckeerealty@att.net', 'McKee Realty - New Contact Message', 
            "Name: $name\nEmail: $email\nPhone: $phone\n\nMessage:\n$message",
            "From: noreply@mckeerealtyinc.com");
        
        $form_sent = true;
    }
}
?>
<section class="bg-navy py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-bold text-white">Get In <span class="text-gold">Touch</span></h1>
        <p class="text-gray-300 mt-2">We're here to help with all your real estate needs</p>
    </div>
</section>

<section class="py-12 md:py-20">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div>
                <h2 class="text-2xl font-bold text-navy mb-6">Send Us a Message</h2>
                
                <?php if ($form_sent): ?>
                <div class="bg-green-50 border border-green-200 text-green-700 p-6 rounded-xl">
                    <h3 class="font-semibold text-lg mb-2">Message Sent! 🎉</h3>
                    <p>Thanks for reaching out. One of our agents will get back to you shortly.</p>
                </div>
                <?php else: ?>
                <form method="POST" class="space-y-4" id="contact-form">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                        <input type="text" name="name" id="cf_name" required class="form-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" id="cf_email" required class="form-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="tel" name="phone" class="form-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message *</label>
                        <textarea name="message" id="cf_message" rows="5" required class="form-input"></textarea>
                    </div>
                    <button type="submit" name="contact_submit" class="btn-primary">Send Message</button>
                </form>
                <?php endif; ?>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h2 class="text-2xl font-bold text-navy mb-6">Visit Our Office</h2>
                <div class="bg-white rounded-xl shadow-md p-6 space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-navy rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-navy">Address</h3>
                            <p class="text-gray-600 mt-1">300 Belle Meade Pt. Ste. B<br>Flowood, MS 39232</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-navy rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-navy">Phone</h3>
                            <a href="tel:6019928141" class="text-gray-600 hover:text-navy transition mt-1 block">(601) 992-8141</a>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-navy rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-navy">Email</h3>
                            <a href="mailto:mckeerealty@att.net" class="text-gray-600 hover:text-navy transition mt-1 block">mckeerealty@att.net</a>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 bg-gray-100 rounded-xl h-64 flex items-center justify-center text-gray-400">
                    <div class="text-center">
                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        <p>Map will display here</p>
                        <p class="text-sm">300 Belle Meade Pt. Ste. B, Flowood, MS</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>