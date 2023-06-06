<!DOCTYPE html>

<html lang="en">
<?php
include "inc/head.inc.php";
?>

    <body id="top">
        <?php
        include "inc/nav.inc.php";
        ?>

        <main>
            <div class="hero has-before" id="aboutus">
                <div class="container">
                    <h1 class="h1 section-title" style="padding-bottom: 100px;" data-reveal="bottom">
                        About <span class="span">Us</span>
                    </h1>
                    <img src="assets/images/hero-banner-bg.png" alt="" class="hero-banner-bg">
                </div>
            </div>
            <div class="container">
                <div class="fade-in">
                    <section id="ourmission">
                        <div class="hero has-before">
                            <h2 class="section-subtitle" data-reveal="bottom" style="font-size: 30px">Our mission</h2>
                        </div>

                        <div class="about-us sub-container">
                            <p>At SIT, we're passionate about bringing the best video games to players around the world.
                                As an online video game distributor, we're committed to providing gamers with a convenient and reliable way to purchase and access their favorite titles.
                                With a global reach and a focus on delivering high-quality games and exceptional customer service, we're dedicated to building a loyal following among
                                gamers of all ages and backgrounds. Whether you're looking for the latest AAA blockbuster title or an indie gem,
                                you can count on SIT to provide you with a seamless and enjoyable gaming experience.</p>
                        </div>
                    </section>

                    <div class="fade-in">
                        <div class="hero has-before" style="margin-top:-200px">
                            <p class="section-subtitle" data-reveal="bottom" style="font-size: 30px;">Our Values</p>
                        </div>
                    </div>

                    <div class="sub-container" data-reveal="bottom">
                        <div class="teams">
                            <!--                                <img src="assets/images/mlp.gif"  alt="Balbasaur Gif">-->
                            <div class="name">Inclusivity</div>

                            <div class="about">Prioritizing inclusivity means striving to create an environment where everyone feels welcome and respected,
                                regardless of their race, gender, sexual orientation, or ability. This could mean implementing diverse character options in games,
                                actively promoting diversity in hiring practices, and moderating forums and chats to ensure that everyone feels safe and supported</div>
                        </div>

                        <div class="teams" data-reveal="bottom">
                            <!--                                <img src="assets/images/char.gif" alt="Charmander Gif">-->
                            <div class="name">Engagement </div>

                            <div class="about">Designing games and features that keep players coming back for more.
                                This could mean offering rewards for completing certain challenges, hosting regular events and tournaments, and providing
                                opportunities for players to interact with each other and with the game's creators</div>
                        </div>

                        <div class="teams" data-reveal="bottom">
                            <!--                                <img src="assets/images/access.gif" style="text-align: center" alt="Squirtle Gif">-->
                            <div class="name">Accessibility </div>

                            <div class="about">Designing games and interfaces that can be easily used by
                                people with disabilities. This could mean providing options for players with visual impairments or hearing loss,
                                designing games with simpler controls or alternate input methods, and ensuring that all content is available in multiple languages</div>
                        </div>

                        <div class="teams" data-reveal="bottom">
                            <!--                                <img src="assets/images/kerbby.gif" alt="Kerbby Gif">-->
                            <div class="name">Fun and Entertainment</div>

                            <div class="about">Prioritize providing its users with a fun and entertaining experience.
                                This could include having a wide range of games, engaging gameplay mechanics, and social features that
                                allow players to connect and compete with each other</div>
                        </div>

                        <div class="teams" data-reveal="bottom">
                            <!--                                <img src="assets/images/squirtle.gif" alt="Squirtle Gif">-->
                            <div class="name">Trust and Transparency</div>

                            <div class="about">Being transparent in its operations, including the terms and conditions of its agreements with game
                                developers and any fees or charges that may apply. This could involve providing clear and concise contracts, being responsive to
                                feedback and concerns, and upholding ethical standards of business practice</div>
                        </div>

                        <div class="teams" data-reveal="bottom">
                            <!--                                <img src="assets/images/squirtle.gif" alt="Squirtle Gif">-->
                            <div class="name">Partnership</div>

                            <div class="about">Building strong partnerships with game developers,
                                working closely with them to support their goals and objectives. This could involve offering marketing support,
                                helping to optimize games for different platforms, and providing feedback and insights to help developers improve their
                                products</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="fade-in">
                <div class="hero has-before" style="margin-top:-200px">
                    <p class="section-subtitle" data-reveal="bottom" style="font-size: 30px;">Locate Us</p>
                </div>
            </div>
            <div class="container">
                <iframe title="our location" class='map' src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.66540302967!2d103.84659831467029!3d1.377433398995402!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da16e96db0a1ab%3A0x3d0be54fbbd6e1cd!2sSingapore%20Institute%20of%20Technology%20(SIT%40NYP)!5e0!3m2!1sen!2ssg!4v1679989428166!5m2!1sen!2ssg" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>

            <div class="fade-in">
                <div class="hero has-before" style="margin-top:-200px">
                    <p class="section-subtitle" data-reveal="bottom" style="font-size: 30px;">Contact Us</p>
                </div>
            </div>
            <?php
            include "contactus.php";
            ?>
        </main>
        <?php
        include "inc/footer.inc.php";
        ?>

    </body>

</html>