<?php /* @var string $content */ ?>

<!doctype html>
<html lang='en-US'>
<head>
    <meta charset='utf-8'/>
    <meta http-equiv='Content-type' content='text/html; charset=utf-8'/>
    <title>Dawae</title>
    <meta name='description' content=''/>
    <meta name='Author' content='Jay Da Wae'/>
    <meta name='viewport' content='width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0'/>
    <link
            href='//fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;amp;subset=latin,latin-ext,cyrillic,cyrillic-ext'
            rel='stylesheet'>
    <link href='<?= Yii::getAlias('@web/plugins/bootstrap/css/bootstrap.min.css') ?>' rel='stylesheet'>
    <link href='<?= Yii::getAlias('@web/css/essentials.css') ?>' rel='stylesheet'>
    <link href='<?= Yii::getAlias('@web/css/layout.css') ?>' rel='stylesheet'>
    <link href='<?= Yii::getAlias('@web/css/color_scheme/green.css') ?>' rel='stylesheet'>
    <link href='<?= Yii::getAlias('@web/css/report.css') ?>' rel='stylesheet'>
    <script src='<?= Yii::getAlias('@web/plugins/jquery/jquery-2.1.4.min.js') ?>'></script>
</head>
<body>
<div id='wrapper' class='clearfix'>
    <aside id='aside'>
        <nav id='sideNav'>
            <ul class='nav nav-list'>
                <li>
                    <a class='dashboard' style='color: red' href='<?= Yii::getAlias('@web/egs/reset/reset') ?>'>
                        <i class='main-icon fa fa-exclamation-triangle'></i>
                        <span>Reset Database</span>
                    </a>
                </li>
                <li>
                    <a class='dashboard' href='<?= Yii::getAlias('@web/egs/#/') ?>'>
                        <i class='main-icon fa fa-home'></i>
                        <span>หน้าแรก</span>
                    </a>
                </li>
                <?php if (!Yii::$app->session->has('id')) { ?>
                <?php } else {
                    $user = Yii::$app->getDb()
                        ->createCommand('SELECT * FROM view_pis_user WHERE id = ' . Yii::$app->session->get('id'))
                        ->queryOne();
                    switch ($user['user_type_id']) {
                        /* THIS IS STUDENT */
                        case 0: ?>
                            <li>
                                <a class='dashboard' href='<?= Yii::getAlias('@web/egs/#/todo') ?>'>
                                    <i class='main-icon fa fa-slack'></i>
                                    <span>รายการที่ต้องทำ</span>
                                </a>
                            </li>
                            <li>
                                <a class='dashboard' href='<?= Yii::getAlias('@web/egs/#/advisor-load') ?>'>
                                    <i class='main-icon fa fa-slack'></i>
                                    <span>ภาระงานอาจารย์</span>
                                </a>
                            </li>
                            <li>
                                <a class='dashboard' href='<?= Yii::getAlias('@web/egs/#/requestList') ?>'>
                                    <i class='main-icon fa fa-slack'></i>
                                    <span>ขอยื่นคำร้อง</span>
                                </a>
                            </li>
                            <li>
                                <a class='dashboard' href='<?= Yii::getAlias('@web/egs/#/request') ?>'>
                                    <i class='main-icon fa fa-slack'></i>
                                    <span>รายการคำร้อง</span>
                                </a>
                            </li>
                            <?php break;
                        /* THIS IS TEACHER */
                        case 1: ?>
                            <li>
                                <a class='dashboard' href='<?= Yii::getAlias('@web/egs/#/request') ?>'>
                                    <i class='main-icon fa fa-slack'></i>
                                    <span>รายการคำร้อง</span>
                                </a>
                            </li>
                            <?php break;
                        /* THIS IS STAFF */
                        case 2:
                        case 3: ?>
                            <li>
                                <a class='dashboard' href='<?= Yii::getAlias('@web/egs/#/advisor-load') ?>'>
                                    <i class='main-icon fa fa-slack'></i>
                                    <span>ภาระงานอาจารย์</span>
                                </a>
                            </li>

                            <li>
                                <a class='dashboard' href='<?= Yii::getAlias('@web/egs/#/calendarList') ?>'>
                                    <i class='main-icon fa fa-slack'></i>
                                    <span>จัดการปฏิทิน</span>
                                </a>
                            </li>
                            <li>
                                <a class='dashboard' href='<?= Yii::getAlias('@web/egs/#/calendar-init') ?>'>
                                    <i class='main-icon fa fa-slack'></i>
                                    <span>ตั้งค่าเริ่มต้นของการสอบ</span>
                                </a>
                            </li>
                            <li>
                                <a class='dashboard' href='<?= Yii::getAlias('@web/egs/#/defense') ?>'>
                                    <i class='main-icon fa fa-slack'></i>
                                    <span>รายการสอบ</span>
                                </a>
                            </li>
                            <li>
                                <a class='dashboard' href='<?= Yii::getAlias('@web/egs/#/request') ?>'>
                                    <i class='main-icon fa fa-slack'></i>
                                    <span>รายการคำร้อง</span>
                                </a>
                            </li>
                            <li>
                                <a class='dashboard' href='<?= Yii::getAlias('@web/egs/#/evaluation-list') ?>'>
                                    <i class='main-icon fa fa-slack'></i>
                                    <span>รายการเอกสารประเมิน</span>
                                </a>
                            </li>
                            <li>
                                <a class='dashboard' href='<?= Yii::getAlias('@web/egs/#/evaluation-all') ?>'>
                                    <i class='main-icon fa fa-slack'></i>
                                    <span>พิมพ์เอกสารประเมิน</span>
                                </a>
                            </li>
                            <li>
                                <a class='dashboard' href='<?= Yii::getAlias('@web/egs/#/request-bypass') ?>'>
                                    <i class='main-icon fa fa-slack'></i>
                                    <span>ให้สิทธ์ยื่นคำร้องนอกเวลา</span>
                                </a>
                            </li>
                            <?php break;
                    }
                    ?>
                <?php } ?>
            </ul>
        </nav>
        <span id='asidebg'></span>
    </aside>
    <header id='header'>
        <button id='mobileMenuBtn'></button>
        <span class='logo pull-left'>
					<img src='<?= Yii::getAlias('@web') ?>/images/logo_light.png' alt='admin panel' height='35'/>
				</span>
        <form method='get' action='page-search.html' class='search pull-left hidden-xs'>
            <input type='text' class='form-control' name='k' placeholder='Search for something...'/>
        </form>
        <nav>
            <ul class='nav pull-right'>
                <li class='dropdown pull-left'>
                    <a href='#' class='dropdown-toggle' data-toggle='dropdown' data-hover='dropdown'
                       data-close-others='true'>
                        <img class='user-avatar' alt='' src='<?= Yii::getAlias('@web') ?>/images/noavatar.jpg'
                             height='34'/>
                        <span class='user-name'>
							<span class='hidden-xs'>
								<?php if (!Yii::$app->session->has('id')) { ?>
                                    Ghost
                                <?php } else {
                                    $user = Yii::$app->getDb()
                                        ->createCommand('SELECT * FROM view_pis_user WHERE id = ' . Yii::$app->session->get('id'))
                                        ->queryOne();
                                    if ($user['user_type_id'] === '0') {
                                        echo $user['student_fname_th'] . ' ' . $user['student_lname_th'];
                                    } else {
                                        echo $user['person_fname_th'] . ' ' . $user['person_lname_th'];
                                    }
                                } ?>

							</span>
					</span>
                    </a>
                    <ul class='dropdown-menu hold-on-click'>
                        <li>
                            <?php if (!Yii::$app->session->has('id')) { ?>
                                <a href='<?= Yii::getAlias('@web/egs/tempo') ?>'>Temporary Login</a>
                            <?php } else { ?>
                                <a href='<?= Yii::getAlias('@web/egs/tempo/logout') ?>'>Logout</a>
                            <?php } ?>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div class='pull-right' style='padding-top: 4px'>
            <a href='<?= Yii::getAlias('@web/egs/tempo/language?lang=th') ?>'>
                <img src='<?= Yii::getAlias('@web/images/th.png') ?>' height='14'/>
            </a>
            <br/>
            <a href='<?= Yii::getAlias('@web/egs/tempo/language?lang=en') ?>'>
                <img src='<?= Yii::getAlias('@web/images/en.png') ?>' height='14'/>
            </a>
        </div>
    </header>
    <section id='middle'>
        <?= $content ?>
    </section>
</div>
<script type='text/javascript'>let plugin_path = ' <?= Yii::getAlias('@web') ?>/plugins/'</script>
<script src='<?= Yii::getAlias('@web/js/app.js') ?>'></script>
</body>
</html>
