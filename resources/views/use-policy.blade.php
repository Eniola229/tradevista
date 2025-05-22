<?php
use App\Models\Product;
?>
@include('components.header')
<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
     @include('components.mobile-nav')
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    @include('components.nav-link')
    <!-- Header Section End -->

    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a>
                        <span>Acceptable Use Policy</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Acceptable Use Policy</h1>
        <p>This Acceptable Use Policy (AUP) outlines the rules and guidelines that all users, including third-party vendors, must follow when using Tradevista Hub. By accessing or using our platform, you agree to comply with this policy.</p>
        
        <h2>1. Prohibited Activities</h2>
        <h3>1.1 Illegal or Fraudulent Activities</h3>
        <ul>
            <li>Selling, promoting, or distributing counterfeit, stolen, or unauthorized goods.</li>
            <li>Engaging in fraudulent, deceptive, or misleading practices.</li>
            <li>Violating any applicable local, national, or international laws.</li>
        </ul>
        
        <h3>1.2 Harmful or Offensive Content</h3>
        <ul>
            <li>Posting or selling items that promote violence, hate speech, discrimination, or harassment.</li>
            <li>Distributing obscene, pornographic, or otherwise inappropriate content.</li>
            <li>Selling or promoting items related to illegal drugs, weapons, or controlled substances.</li>
        </ul>
        
        <h3>1.3 Security Violations</h3>
        <ul>
            <li>Attempting to gain unauthorized access to our systems, data, or user accounts.</li>
            <li>Uploading or distributing malware, viruses, or other harmful software.</li>
            <li>Engaging in activities that may harm the integrity or security of our platform.</li>
        </ul>
        
        <h3>1.4 Intellectual Property Violations</h3>
        <ul>
            <li>Infringing on the copyrights, trademarks, or other intellectual property rights of others.</li>
            <li>Selling or distributing pirated software, media, or counterfeit goods.</li>
        </ul>
        
        <h3>1.5 Unfair or Deceptive Practices</h3>
        <ul>
            <li>Manipulating transactions, reviews, or ratings.</li>
            <li>Engaging in price-fixing, false advertising, or deceptive marketing.</li>
            <li>Creating multiple accounts to bypass platform restrictions.</li>
        </ul>
        
        <h2>2. Vendor Responsibilities</h2>
        <ul>
            <li>Provide accurate and up-to-date business and contact information.</li>
            <li>Ensure that all products and services comply with legal and ethical standards.</li>
            <li>Maintain a high standard of customer service, including prompt responses to customer inquiries.</li>
            <li>Comply with all financial and tax regulations applicable to their business.</li>
        </ul>
        
        <h2>3. Enforcement and Consequences</h2>
        <ul>
            <li>Issue a warning or request corrective action.</li>
            <li>Temporarily or permanently suspend the vendor’s account.</li>
            <li>Remove prohibited content, listings, or products.</li>
            <li>Report unlawful activities to relevant authorities.</li>
            <li>Take legal action if necessary.</li>
        </ul>
        
        <h2>4. Reporting Violations</h2>
        <p>If you believe a vendor or user has violated this AUP, please contact us at <a href="mailto:tradevista2015@gmail.com">tradevista2015@gmail.com</a>. We will investigate and take appropriate action.</p>
        
        <h2>5. Amendments to This Policy</h2>
        <p>We reserve the right to modify this Acceptable Use Policy at any time. Changes will be effective upon posting on our platform. Your continued use of our platform constitutes acceptance of any modifications.</p>
        
        <h2>6. Contact Information</h2>
        <p>Tradevista Hub<br>
        Address: No 7 Oloruntoba beside Mighty Miracle College, Kadupe, Soka, Ibadan, Oyo State.<br>
        Email: <a href="mailto:tradevista2015@gmail.com">tradevista2015@gmail.com</a><br>
        Tel: +234 8132612077</p>
        
        <h1 class="text-center mt-5">Handling Chargebacks from Customers</h1>
        
        <h2>1. Chargeback Notification & Review</h2>
        <ul>
            <li>We receive a chargeback request from the payment processor.</li>
            <li>The chargeback reason is reviewed (e.g., fraud, product not received, item not as described, duplicate transaction).</li>
            <li>We notify the vendor about the chargeback and request relevant information.</li>
        </ul>
        
        <h2>2. Vendor Response & Evidence Collection</h2>
        <ul>
            <li>Vendors must provide supporting documents, such as:</li>
            <ul>
                <li>Order confirmation and shipping details (tracking number, delivery proof).</li>
                <li>Customer communication records.</li>
                <li>Refund or replacement offers (if applicable).</li>
            </ul>
            <li>We compile all necessary evidence to dispute the chargeback if appropriate.</li>
        </ul>
        
        <h2>3. Chargeback Resolution Process</h2>
        <ul>
            <li>If the vendor provides sufficient evidence, we submit a dispute to the payment processor.</li>
            <li>If the dispute is successful, the chargeback is reversed, and the vendor retains the payment.</li>
            <li>If the dispute is unsuccessful, the chargeback amount is deducted from the vendor’s account.</li>
        </ul>
        
        <h2>4. Preventive Measures & Vendor Compliance</h2>
        <ul>
            <li>Vendors are encouraged to provide clear product descriptions and high-quality service to minimize disputes.</li>
            <li>Regular monitoring of chargeback rates to ensure compliance with acceptable thresholds.</li>
            <li>Vendors with excessive chargebacks may face penalties, including account suspension or termination.</li>
        </ul>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Contact Section End -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@include('components.footer')
</html>