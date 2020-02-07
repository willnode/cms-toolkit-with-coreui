
<?php control_table([
    'toolbar' => get_control_buttons([[
      'href' => 'create',
      'label' => 'New',
      'icon' => 'fa fa-plus',
      'style' => 'btn btn-success ml-2',
    ]])
  ], [
    ['field'=>'username', 'label'=>'Username'],
    ['field'=>'name', 'label'=>'Name'],
    ['field'=>'email', 'label'=>'Email'],
    ['field'=>'login_id',
     'label'=>'Action',
     'formatter'=> get_control_buttons([[
        'href'=>'edit/${value}',
        'style'=>'btn btn-sm btn-warning',
        'icon'=>'fa fa-edit',
      ], [
        'href'=>'delete/${value}',
        'style'=>'btn btn-sm btn-danger',
        'icon'=>'fa fa-trash',
        'confirm'=>'Are you sure?'
      ]])
    ],
  ]) ?>