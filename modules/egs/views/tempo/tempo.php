<?php /* @var array $temp */ ?>
<div id="content" class="padding-20">
    <div class="panel panel-default">
        <div class="panel-heading panel-heading-transparent">
            <div class="header">
                <strong>TEMPO</strong>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-vertical-middle nomargin">
                <thead>
                <tr>
                    <th>username</th>
                    <th>person_name</th>
                    <th>user_type</th>
                    <th>program</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($temp as $user) { ?>
                    <tr style="text-align: center">
                        <td><?= $user['username'] ?></td>
                        <td><?php
                            if ($user['user_type_id'] === '0') {
                                echo $user['student_fname_th'] . ' ' . $user['student_lname_th'];
                            } else {
                                echo $user['person_fname_th'] . ' ' . $user['person_lname_th'];
                            }
                            ?></td>
                        <td>
                            <?php switch ($user['user_type_id']) {
                                case 0:
                                    echo 'นักศึกษา';
                                    break;
                                case 1:
                                    echo 'อาจารย์';
                                    break;
                                case 2:
                                    echo 'แอดมิน';
                                    break;
                                case 3:
                                    echo 'เจ้าหน้าที่';
                                    break;
                            } ?>
                        </td>
                        <td><?= $user['program'] === null ? '-' : $user['program'] ?></td>
                        <td>
                            <a href="<?= Yii::getAlias('@web/egs/tempo/login?id=' . $user['id']) ?>"
                               class="btn btn-success">login</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>