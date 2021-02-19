<?php

defined('C5_EXECUTE') or die('Access Denied.');

/** @var array $templateOptions */
/** @var string $selectedTemplate */
/** @var string $id */
/** @var array $classesSelect */
/** @var array $classesSelected */
/** @var string $attributes */
?>

<div class="control-group">
    <div class="form-group">
        <i class="fa fa-question-circle launch-tooltip"
           title="<?php echo t("Always start with the 'Open' template. Then add one or more blocks. Then add a wrapper block with template 'Close'.") ?>"
        ></i>

        <?php
        echo $form->label('template', t('Select a template') . '*');
        echo $form->select('template', $templateOptions, $selectedTemplate);
        ?>
    </div>
</div>

<div class="settings-open">
    <div class="form-group">
        <i class="fa fa-question-circle launch-tooltip"
           title="<?php echo t('Any CSS classes the container element should get. You can add your own class(es) to the list of suggested classes from your theme, of course.') ?>"
        ></i>

        <?php
        echo $form->label('classes', t('Classes'));
        echo $form->selectMultiple('classes', $classesSelect, $classesSelected, [
            'placeholder' => t('Leave blank to use none'),
        ]);
        ?>
    </div>

    <div class="form-group">
        <i class="fa fa-question-circle launch-tooltip"
           title="<?php echo t('This is the identifier (id) of the wrapper element.') ?>"
        ></i>

        <?php
        echo $form->label('id', t('ID'));
        echo $form->text('id', $id, [
            'placeholder' => t('Leave blank to use none'),
        ]);
        ?>
    </div>

    <div class="form-group">
        <i class="fa fa-question-circle launch-tooltip"
           title="<?php echo t("These can be HTML5 data attributes, or any kind of attributes. They are injected in the tag.") ?>"
        ></i>

        <?php
        echo $form->label('attributes', t('Attributes'));
        echo $form->textarea('attributes', $attributes, [
            'placeholder' => t('Leave blank to use none'),
        ]);
        ?>
    </div>
</div>

<script>
$("#classes").selectize({
    plugins: ['remove_button'],
    create: function(input) {
        return {
            value: input,
            text: input
        }
    }
});

$('#template').change(function() {
    $('.settings-open').toggle($(this).val() === 'open.php');
}).trigger('change');
</script>
