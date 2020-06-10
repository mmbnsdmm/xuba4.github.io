<?php
use dmstr\widgets\Alert;
?>
<div class="content-wrapper">
    <?=$this->render('_section') ?>
    <section class="content">
        <?= Alert::widget() ?>
        <?= $content ?>
    </section>
</div>
<?=$this->render('_footer') ?>
<?=$this->render('_aside') ?>