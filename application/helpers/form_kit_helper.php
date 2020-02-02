<?php


function control_error($name) {
    echo form_error($name, '<div class="col-12 alert alert-danger" role="alert">', '</div>');
}
/**
 * Internal usage; Add label and validation error message before it.
 */
function control_label(&$attr) {
    control_error(issetor($attr['name'], ''));
    ?><label class="col-md-3 col-form-label" for="<?=issetor($attr['name'], '')?>"><?=issetor($attr['label'], '')?></label><?php
}

/**
 * Internal usage; Expand HTML attributes with additional checks
 */
function control_attrs(&$attr) {
    isset($attr['name']) AND !isset($attr['id']) AND $attr['id'] = $attr['name'];
    empty($attr['class']) AND $attr['class'] = 'form-control';
    unset($attr['label']);
    if (isset($attr['value'], $attr['name']) && set_value($attr['name']))
        $attr['value'] = set_value($attr['name']);
    return implode(' ', array_map(
        function ($k, $v) { return $v === FALSE ? '' : $k .'="'. htmlspecialchars($v) .'"'; },
        array_keys($attr), $attr
    ));
}

/**
 * Readonly, unsubmittable form input
 */
function control_div($attr, $raw = FALSE) {
    $value = $attr['value'];
    unset($attr['value'])
    ?>
    <div class="form-group row">
        <?php control_label($attr) ?>
        <div class="col-md-9">
            <div <?= control_attrs($attr) ?>><?= $raw ? (is_callable($value) ? $value() : $value) : htmlspecialchars($value) ?></div>
        </div>
    </div>
    <?php
}

/**
 * General form <input>
 */
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

/**
 * Form submit button
 */
function control_submit() {
    control_input(['name'=>'form_submit','type'=>'submit', 'class'=>'form-control btn btn-primary', 'value'=>"Submit"]);
}

/**
 * General form <textarea>
 */
function control_textarea($attr) {
    $value = set_value($attr['name'], isset($attr['value']) ? $attr['value'] : '', FALSE);
    unset($attr['value']);
    ?>
    <div class="form-group row">
        <?php control_label($attr) ?>
        <div class="col-md-9">
            <textarea <?= control_attrs($attr) ?>><?=htmlspecialchars($value)?></textarea>
        </div>
    </div>

    <?php
}

/**
 * General form <select> (with 'options' as assoc PHP child)
 */
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

/**
 * Input file with download button if file actually exist
 */
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
            <?php control_buttons([[
                'label'=>'Download',
                'icon'=>'fa fa-download',
                'style'=>'btn btn-outline-success',
                'value'=>base_url($file),
                'kind'=>'download'
                ]]) ?>
            <?php if (!$readonly) : ?>
                <?php control_buttons([[
                    'name'=>$name.'_delete',
                    'label'=>'Delete',
                    'icon'=>'fa fa-trash',
                    'style'=>'btn btn-outline-danger',
                    'confirm'=>'Are you sure?'
                    ]]) ?>
            <?php endif ?>
            <span><?=$attr['value']?></span>
            </div>
            <?php endif ?>
        </div>
    </div>
    <?php
}

function control_buttons($buttons) {
    ?>
    	<div class="btn-group-toggle d-inline-flex" data-toggle="buttons">
            <?php foreach ($buttons as $button):
                $name = isset($button['name']) ? $button['name'] : '';
                $label = isset($button['label']) ? $button['label'] : '';
                $value = isset($button['value']) ? $button['value'] : 'y';
                $icon = isset($button['icon']) ? $button['icon'] : "fa fa-info";
                $style = isset($button['style']) ? $button['style'] : "btn btn-outline-primary";
                $conf = isset($button['confirm']) ? "confirm('$button[confirm]')" : 'true';
                $type = isset($button['type']) ? $button['type'] : 'submit';
                switch ($type) {
                    case 'submit':
                        ?>
                            <label onclick="$(this).button('toggle'); if(<?=$conf?>) $(this).parents('form')[0].submit()"
                            class="mb-0 mr-2 <?=$style?>"><i class="<?=$icon?>"></i>
                            <input type="checkbox" name="<?=$name?>" value="<?=$value?>">&nbsp;<?=$label?></label>
                        <?php
                        break;
                    case 'download':
                        ?>
                            <a onclick="return <?=$conf?>" href="<?=$value?>" class="mr-2 <?=$style?>" download>
                            <i class="<?=$icon?>"></i>&nbsp;<?=$label?></a>
                        <?php
                        break;
                    case 'link':
                        ?>
                            <a onclick="return <?=$conf?>" href="<?=$value?>" class="mr-2 <?=$style?>">
                            <i class="<?=$icon?>"></i>&nbsp;<?=$label?></a>
                        <?php
                        break;
                    case 'copy':
                        ?>
                            <button onclick="if(<?=$conf?>) prompt('Copy this text (Ctrl+C):', '<?=htmlspecialchars($value)?>'); return false" class="mr-2 <?=$style?>">
                            <i class="<?=$icon?>"></i>&nbsp;<?=$label?></button>
                        <?php
                        break;
                }
            endforeach ?>
        </div>
    <?php
}

/**
 * Input file, but for image
 */
function control_image($attr) {
    control_file($attr, TRUE);
}
