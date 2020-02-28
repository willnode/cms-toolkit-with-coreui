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

function control_attrs_data(&$attr) {
    return implode(' ', array_map(
        function ($k, $v) { return $v === FALSE ? '' : 'data-'.$k .'="'. htmlspecialchars($v) .'"'; },
        array_keys($attr), $attr
    ));
}

/**
 * Readonly, unsubmittable form input
 */
function control_div($attr) {
    $value = $attr['value'];
    unset($attr['value'])
    ?>
    <div class="form-group row">
        <?php control_label($attr) ?>
        <div class="col-md-9">
            <div <?= control_attrs($attr) ?>><?= is_callable($value) ? $value() : htmlspecialchars($value) ?></div>
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
                <option value="<?= $v->{$option_key} ?>" <?=set_select($attr['name'], $value, $value==$v->{$option_key})?>>
                    <?= $v->{$option_value}?></option>
                <?php endforeach ?>
            </select>
        </div>
    </div>
    <?php
}

/**
 * Input file with download button if file actually exist.
 * (proper handling for backend should be done with control_file_upload)
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

/**
 * Input file, but for image
 */
function control_image($attr) {
    control_file($attr, TRUE);
}

/**
 * Input behaviors via button (like alternative submit for specific action or other UX behaviours)
 */
function control_buttons($buttons, $style = 'btn-group') {
    ?>
    	<div class="<?=$style?> d-inline-flex no-wrap">
            <?php foreach ($buttons as $button):
                $name = isset($button['name']) ? $button['name'] : '';
                $label = isset($button['label']) ? '&nbsp;&nbsp;'.$button['label'] : '';
                $icon = isset($button['icon']) ? $button['icon'] : "fa fa-info";
                $style = isset($button['style']) ? $button['style'] : "btn btn-outline-primary";
                $conf = isset($button['confirm']) ? "confirm('$button[confirm]')" : 'true';
                if (isset($button['href'])) {
                    $value = $button['href'];
                    $type = isset($button['type']) ? $button['type'] : 'link';
                } else {
                    $value = isset($button['value']) ? $button['value'] : 'y';
                    $type = isset($button['type']) ? $button['type'] : 'submit';
                }
                switch ($type) {
                    case 'submit':
                        ?>
                            <div onclick="if(<?=$conf?>) {var f = $(this).parents('form')[0]; f['<?=$name?>'].checked=true;f.submit();}"
                            class="<?=$style?>" style="cursor: pointer"><i class="<?=$icon?>"></i>
                            <input type="checkbox" hidden name="<?=$name?>" value="<?=$value?>"><?=$label?></div>
                        <?php
                        break;
                    case 'download':
                        ?>
                            <a onclick="return <?=$conf?>" href="<?=$value?>" class="<?=$style?>" download>
                            <i class="<?=$icon?>"></i><?=$label?></a>
                        <?php
                        break;
                    case 'link':
                        ?>
                            <a onclick="return <?=$conf?>" href="<?=$value?>" class="<?=$style?>">
                            <i class="<?=$icon?>"></i><?=$label?></a>
                        <?php
                        break;
                    case 'copy':
                        ?>
                            <button onclick="if(<?=$conf?>) prompt('Copy this text (Ctrl+C):', $(this).data('value')); return false" data-value="<?=htmlspecialchars($value)?>" class="<?=$style?>">
                            <i class="<?=$icon?>"></i><?=$label?></button>
                        <?php
                        break;
                }
            endforeach ?>
        </div>
    <?php
}

/**
 * Table template for 1:1 Bootstrap AJAX driven template,
 * Only feasible to be used once per page
 */
function control_table($data, $columns) {
    $id = isset($data['id']) ? $data['id'] : 'table';
    $class = isset($data['class']) ? $data['class'] : 'table-sm';
    $script = '';
    unset($data['id']);
    unset($data['class']);
    empty($data['url']) AND $data['url'] = 'get?'.$_SERVER['QUERY_STRING'];
    empty($data['search']) AND $data['search'] = 'true';
    empty($data['toggle']) AND $data['toggle'] = 'table';
    empty($data['pagination']) AND $data['pagination'] = 'true';
    empty($data['side-pagination']) AND $data['side-pagination'] = 'server';

    if (isset($data['toolbar'])) {
        ?><div id="toolbar"><?=$data['toolbar']?></div><?php
        $data['toolbar']='#toolbar';
        empty($data['toolbar-align']) AND $data['toolbar-align'] = 'right';
    }
    if (isset($data['detail-formatter'])) {
        $jsname = 'detailFormatter';
        $script .= 'function '.$jsname.'(index, data){return `'.$data['detail-formatter']."`}\n";
        $data['detail-formatter'] = $jsname;
        $data['detail-view'] = 'true';
    }
    ?>
    <table id=<?=$id?> class="<?=$class?>" <?=control_attrs_data($data)?>><thead><tr>
    <?php foreach ($columns as $column) {
        $label = $column['label'];
        unset($column['label']);
        if (isset($column['formatter'])) {
            $jsname = $column['field'].'Format';
            $script .= 'function '.$jsname.'(value, data, index){return `'.$column['formatter']."`}\n";
            $column['formatter'] = $jsname;
            empty($column['width']) AND $column['width'] = '1'; // fit with content
        }
        ?><th <?=control_attrs_data($column)?>><?=$label?></th><?php
    } ?>
    </tr></thead></table>
    <script><?=$script?></script><?php
}

/**
 * Like control_buttons() but returns the output instead of echo (useful for control_table)
 */
function get_control_buttons($buttons, $style = 'btn-group') {
    ob_start();
    control_buttons($buttons, $style);
    $str = ob_get_contents();
    ob_end_clean();
    return $str;
}
