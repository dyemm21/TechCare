<?php
$section = isset($_POST['section']) ? $_POST['section'] : 'profile';
?>


<div class="page-dashboard-first-back">
    <div class="page-dashboard-first-container">
        <div class="page-dashboard">
            <div class="page-dashboard-menu">
                <div class="page-dashboard-menu-head">
                    <img src="/public/dashboard_icon.svg" alt="dashboard-icon"/>
                    <h1>Dashboard</h1>
                </div>
                <span></span>
                <div class="page-dashboard-menu-nav">
                    <form method="post" class="page-dashboard-menu-sections">
                        <button type="submit" name="section" value="profile">
                            <img src="/public/profile_icon.svg" alt="profile-icon" class="page-dashboard-menu-icon"/>
                            <h3>Profil</h3>
                        </button>
                        <button type="submit" name="section" value="password">
                            <img src="/public/password_icon.svg" alt="contact-icon" class="page-dashboard-menu-icon"/>
                            <h3>Hasło</h3>
                        </button>
                        <button type="submit" name="section" value="address">
                            <img src="/public/address_icon.svg" alt="profile-icon" class="page-dashboard-menu-icon"/>
                            <h3>Adres</h3>
                        </button>
                        <button type="submit" name="section" value="contact">
                            <img src="/public/contact_icon.svg" alt="contact-icon" class="page-dashboard-menu-icon"/>
                            <h3>Kontakt</h3>
                        </button>
                        <button type="submit" name="section" value="orders">
                            <img src="/public/order_icon.svg" alt="order-icon" class="page-dashboard-menu-icon"/>
                            <h3>Zamówienia</h3>
                        </button>
                    </form>
                    <div class="page-dashboard-menu-user-profile">
                        <img src="/public/men1.jpg" alt="user-profile">
                        <h3>Jan Kowalski</h3>
                        <p>jkowalski@gmail.com</p>
                    </div>
                </div>
            </div>
            <div class="page-dashboard-content">
                <div class="page-about-content-top">
                    <?php if ($section === 'profile'): ?>
                    <div class="page-about-content-top-first">
                        <div class="page-about-content-top-title">
                            <h5>Your photo</h5>
                            <p>This will be displayed on your profile.</p>
                        </div>
                        <div class="page-about-content-imageContainer">
                            <img src="/public/noavatar.png"/>
                            <div class="page-about-content-image-update">Update</div>
                            <div class="page-about-content-image-delete">Delete</div>
                        </div>
                    </div>
                    <div class="page-about-content-top-second">
                        <div class="page-about-content-top-second-data">
                            <h5>Firstname</h5>
                            <input type="text" placeholder="Firstname" value="Jan"/>
                        </div>
                        <div class="page-about-content-top-second-data">
                            <h5>Lastname</h5>
                            <input type="text" placeholder="Lastname" value="Kowalski"/>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if ($section === 'password'): ?>
                        <div class="page-about-content-top-second">
                            <div class="page-about-content-top-second-data">
                                <h5>Last Password</h5>
                                <input type="password" placeholder="Password" value="dsab123"/>
                            </div>
                            <div class="page-about-content-top-second-data">
                                <h5>New Password</h5>
                                <input type="password" placeholder="Password" value="sdajkk123d45"/>
                            </div>
                            <div class="page-about-content-top-second-data">
                                <h5>Repeat Password</h5>
                                <input type="password" placeholder="Password" value="sdajkk123d45"/>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($section === 'address'): ?>
                        <div class="page-about-content-top-second-contact">
                            <div class="page-about-content-top-first-contact-subsections">
                                <div class="page-about-content-top-second-contact-data">
                                    <h5>City</h5>
                                    <input type="text" placeholder="City" value="Lodz"/>
                                </div>
                                <div class="page-about-content-top-second-contact-data">
                                    <h5>Post Code</h5>
                                    <input type="text" placeholder="Postal Code" value="90-001"/>
                                </div>
                            </div>
                            <div class="page-about-content-top-first-contact-subsections">
                                <div class="page-about-content-top-second-contact-data">
                                    <h5>Address</h5>
                                    <input type="text" placeholder="Street" value="Radwanska"/>
                                </div>
                                <div class="page-about-content-top-second-contact-data">
                                    <h5>Number</h5>
                                    <input type="number" placeholder="Number" value="42"/>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($section === 'contact'): ?>
                        <div class="page-about-content-top-second">
                            <div class="page-about-content-top-second-data">
                                <h5>Phone Number</h5>
                                <input type="number" placeholder="Phone Number" value="987654321"/>
                            </div>
                            <div class="page-about-content-top-second-data">
                                <h5>Email</h5>
                                <input type="email" placeholder="Email" value="jkowalski@gmail.com"/>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($section === 'orders'): ?>
                        <div class="page-about-content-top-first">
                            <div class="page-about-content-top-title">
                                <h5>Your photo</h5>
                                <p>This will be displayed on your profile.</p>
                            </div>
                            <div class="page-about-content-imageContainer">
                                <img src="/public/noavatar.png"/>
                                <div class="page-about-content-image-update">Update</div>
                                <div class="page-about-content-image-delete">Delete</div>
                            </div>
                        </div>
                        <div class="page-about-content-top-second">
                            <div class="page-about-content-top-second-data">
                                <h5>Firstname</h5>
                                <input type="text" placeholder="Firstname" value="Daniel"/>
                            </div>
                            <div class="page-about-content-top-second-data">
                                <h5>Lastname</h5>
                                <input type="text" placeholder="Lastname" value="Karolak"/>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="page-about-content-bottom">
                    Test test
                </div>
            </div>
        </div>
    </div>
</div>