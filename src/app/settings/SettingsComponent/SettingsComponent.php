<?php
$section = isset($_POST['section']) ? $_POST['section'] : 'general';
?>

<div class="settings-first-back">
    <div class="settings-first-container">
        <div class="settings-first-content">
            <div class="services-first-head">
                <h3>Settings</h3>
                <form method="post" class="settings-first-sections">
                    <button type="submit" name="section" value="general"
                            class=" <?= $section === 'general' ? 'settings-first-active' : 'settings-first-section' ?>">
                        General
                    </button>
                    <button type="submit" name="section" value="password"
                            class=" <?= $section === 'password' ? 'settings-first-active' : 'settings-first-section' ?>">
                        Password
                    </button>
                    <button type="submit" name="section" value="notifications"
                            class=" <?= $section === 'notifications' ? 'settings-first-active' : 'settings-first-section' ?>">
                        Notifications
                    </button>
                </form>

                <?php if ($section === 'general'): ?>
                <div id="general-section" class="settings-first-subsections">
                    <div class="settings-first-photo">
                        <div class="settings-first-photo-title">
                            <h5>Your photo</h5>
                            <p>This will be displayed on your profile.</p>
                        </div>
                        <div class="settings-first-photo-image-container">
                            <img src="/public/noavatar.png" class="settings-first-img"/>
                            <div class="settings-first-update">Update</div>
                            <div class="settings-first-delete">Delete</div>
                        </div>
                    </div>
                    <span class="settings-first-line"></span>
                    <div class="settings-first-user-email">
                        <div class="settings-first-data">
                            <h5>Firstname</h5>
                            <input type="text" placeholder="Firstname" value="Daniel"/>
                        </div>
                        <div class="settings-first-data">
                            <h5>Lastname</h5>
                            <input type="text" placeholder="Lastname" value="Karolak"/>
                        </div>
                        <div class="settings-first-data">
                            <h5>Email</h5>
                            <input type="email" placeholder="Email" value="danielkarolak@gmail.com"/>
                        </div>
                    </div>
                    <span class="settings-first-line"></span>
                    <button class="settings-first-button-save">Save Changes</button>
                </div>
                <?php endif; ?>

                <?php if ($section === 'password'): ?>
                <div id="general-section" class="settings-first-subsections-password">
                    <div class="settings-first-password">
                        <h5>Last Password</h5>
                        <input type="password" placeholder="Last Password" value="ksdjha12"/>
                    </div>
                    <div class="settings-first-password">
                        <h5>New Password</h5>
                        <input type="password" placeholder="New Password" value="asdmasdojas12"/>
                    </div>
                    <div class="settings-first-password">
                        <h5>Repeat Password</h5>
                        <input type="password" placeholder="Repeat Password" value="asdmasdojas12"/>
                    </div>
                    <span class="settings-first-line"></span>
                    <button class="settings-first-button-save">Save Changes</button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
