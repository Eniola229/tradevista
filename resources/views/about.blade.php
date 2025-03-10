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
                        <span>About</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Blog Section Begin -->
    <section class="blog spad">
      <div class="container">
   <div class="container mt-5">
    <div class="container mt-4 p-4 bg-light border rounded">
        <h2 class="text-primary border-bottom pb-2">About TradeVista</h2>
        <p><strong>TradeVista Hub</strong> is a registered platform that connects buyers and sellers. Many sellers struggle to find buyers, just as some buyers don’t always know where to find the items they need. Even those who can visit the market often lack the time to do so. TradeVista Hub solves this problem by partnering with logistics providers to deliver products from sellers directly to buyers’ doorsteps.
        <br>
        It's important to note that TradeVista does not sell goods directly but acts as a bridge between buyers and sellers. A small service fee is added to the sale price to maintain the platform and compensate staff. Since everyone has something to buy, sell, or both, TradeVista is committed to promoting financial freedom.
        <br>
        As part of its Business Forum, TradeVista offers a ₦2,035 bonus to every registered member, which becomes withdrawable once it reaches ₦10,000. Members can grow their balance by inviting others to join, with referral bonuses extending up to the second downline. However, to start listing products for sale, a registered member must pay a small activation fee of ₦2,875 to enable product advertisements.
        <br>
        Additionally, TradeVista collects gently used items from donors and distributes them to less privileged individuals in need.
        </p>
        
        <h2 class="text-primary border-bottom pb-2">Experience Fast Delivery and Online Shopping Convenience</h2>
        <p>Our dedicated team is always available to ensure a seamless shopping experience on TradeVista. For complaints and inquiries, call us at 08123362650. To place an order, you can also reach us at the same number. You can chat with us on WhatsApp (08132612077) or email us at tradevista2015@gmail.com.</p>
        
        <h2 class="text-primary border-bottom pb-2">How Can I Become a Seller on TradeVista?</h2>
        <p>You can partner with TradeVista as a seller, online or offline! Simply chat with us on WhatsApp at 08132612077 or contact our support team. With our large customer base, we can help you reach a wider audience and sell more of your products.</p>
        
        <h2 class="text-primary border-bottom pb-2">How to Shop on TradeVista</h2>
        <p>Once you visit TradeVista’s unique website, you can browse displayed items or explore categories to find what you need. Simply select your desired product and place an order. After making your payment, you can rest assured that your item will be delivered to your doorstep within 24 hours.</p>
        
        <h2 class="text-primary border-bottom pb-2">Delivery Options and Timelines</h2>
        <p>Get your products delivered within 24 hours when you shop through TradeVista! Our logistics team ensures your items reach your doorstep. However, in cases where the buyer and seller are nearby, they may connect directly for a quicker exchange. Regardless, payment is made to TradeVista, and we ensure the product is in good condition before releasing the payment to the seller.</p>
        
        <h2 class="text-primary border-bottom pb-2">Report a Product</h2>
        <p>TradeVista does not sell items directly but connects buyers with sellers. Our return policy is only valid within 24 hours of receiving the item. However, we encourage buyers and sellers to report any malicious activity to TradeVista support. Our team will then contact the seller to resolve the issue.</p>
        
        <h2 class="text-primary border-bottom pb-2">Safety Tips</h2>
        <p>TradeVista prioritizes the safety of its customers and staff by putting necessary measures in place. However, it’s important for everyone to remain cautious. We recommend that customers and logistics officers meet at the nearest bus stop to the buyer’s residence, in an open and visible area where passersby can see them.</p>
        
      <div class="container my-5">
        <h2 class="text-primary border-bottom pb-2">Frequently Asked Questions (FAQ)</h2>
        <p><strong>Question: What is TradeVista all about?</strong> A platform connecting buyers and sellers, with referral bonuses. TradeVista also collects gently used items from donors and delivers them to less privileged individuals who express interest.</p>
        <p><strong>Question: Can I post my products?</strong> Yes! Sellers can advertise, and we manage delivery. We partner with logistics providers who handle pickup and delivery to interested buyers. Once the buyer receives the product, the seller’s account is credited within 24 hours.</p>
        <p><strong>Question: Is registration free?</strong> Yes, but an activation fee of ₦2,875 is required to list products. Upon signing up, your wallet is credited with ₦2,035, which grows as you invite others to join.</p>
    </div>  
        <h2 class="text-primary border-bottom pb-2">Contact Us</h2>
        <p>Call us at 08123362650 or email us at <a href="mailto:tradevista2015@gmail.com">tradevista2015@gmail.com</a></p>
    </div>
</div>

     </div>
    </section>
<!-- Contact Section End -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@include('components.footer')
</html>
