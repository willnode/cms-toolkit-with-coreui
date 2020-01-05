<?php

function control_label(&$attr) {
    echo form_error(issetor($attr['name'], ''), '<div class="col-12 alert alert-danger" role="alert">', '</div>');
    ?><label class="col-md-3 col-form-label" for="<?=issetor($attr['name'], '')?>"><?=issetor($attr['label'], '')?></label><?php
}

function control_attrs(&$attr) {
    if (isset($attr['name'])) $attr['id'] = $attr['name'];
    isset($attr['class']) ?: $attr['class'] = 'form-control';
    if (isset($attr['disabled']) && $attr['disabled'] === false) unset($attr['disabled']);
    if (isset($attr['readonly']) && $attr['readonly'] === false) unset($attr['readonly']);
    unset($attr['label']);
    if (isset($attr['value']) && isset($attr['name']) && set_value($attr['name']))
        $attr['value'] = set_value($attr['name']);
    return implode(' ', array_map(
        function ($k, $v) { return $k .'="'. htmlspecialchars($v) .'"'; },
        array_keys($attr), $attr
    ));
}

function control_input($attr) {
    ?>
    <div class="form-group row">
        <?php control_label($attr) ?>
        <div class="col-md-9">
            <input <?= control_attrs($attr) ?>>
        </div>
    </div>
    <?php
}

function control_submit() {
    control_input(['type'=>'submit', 'class'=>'form-control btn btn-primary', 'value'=>"Submit"]);
}

function control_textarea($attr) {
    $value = set_value($attr['name'], isset($attr['value']) ? $attr['value'] : '', FALSE);
    unset($attr['value'])
    ?>
    <div class="form-group row">
        <?php control_label($attr) ?>
        <div class="col-md-9">
            <textrea <?= control_attrs($attr) ?>><?=htmlspecialchars($value)?></textarea>
        </div>
    </div>

    <?php
}

function control_option($attr) {
    $value = $attr['value'];
    $options = $attr['options'];
    $option_key = $attr['option_key'];
    $option_value = $attr['option_value'];
    unset($attr['options']);
    unset($attr['option_key']);
    unset($attr['option_value']);
    unset($attr['value']);
    ?>
    <div class="form-group row">
        <?php control_label($attr) ?>
        <div class="col-md-9">
            <select <?= control_attrs($attr) ?>>
                <?php foreach ($options as $v) : ?>
                <option value="<?= $v->{$option_key} ?>" <?=set_select($attr['name'], $value==$v->{$option_key})?>>
                    <?= $v->{$option_value}?></option>
                <?php endforeach ?>
            </select>
        </div>
    </div>
    <?php
}

function control_file($attr, $image = FALSE) {
    $file = "./uploads/$attr[folder]/$attr[value]";
    $name = issetor($attr['name'], '');
    $readonly = isset($attr['readonly']) && $attr['readonly'] !== FALSE;
    ?>
    <div class="form-group row">
        <?php control_label($attr) ?>
        <div class="col-md-9">
            <?php if (!$readonly) : ?>
            <input type="file" <?= control_attrs($attr) ?>>
            <?php endif ?>
            <?php if ($attr['value'] && file_exists($file)) : ?>
            <div class="form-control mt-2 p-2 h-auto">
            <?php if ($image) : ?>
            <img src="<?=base_url($file)?>" alt="" class="mb-2 d-block" style="max-height:200px;max-width:100%">
            <?php endif ?>
            <a href="<?=base_url($file)?>" class="btn btn-info mr-auto" download>
            <i class="fa fa-download"></i> Download</a>
            <?php if (!$readonly) : ?>
            <div class="btn-group-toggle d-inline-block" data-toggle="buttons">
            <label onclick="$(this).button('toggle'); if(confirm('Are you sure?')) $(this).parents('form')[0].submit()"
            class="btn btn-outline-danger mb-0 mr-auto"><i class="fa fa-trash"></i>
            <input type="checkbox" name="<?=$name?>_delete" value="y"> Delete</label></div>
            <?php endif ?>
            <span><?=$attr['value']?></span>
            </div>
            <?php endif ?>
        </div>
    </div>
    <?php
}

function control_image($attr) {
    control_file($attr, TRUE);
}
