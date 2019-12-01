<?php

$otGrp = array (

  'id' => 
      array (
        'row'   => 1,
        'type'  => 'hidden',
        'label' => 'Id',
      ),
  'id_guid' => 
      array (
        'row' => 0,
        'type' => 'hidden',
        'label' => 'Id Guid',
      ),
  'id_parent_guid' => 
      array (
        'row' => 0,
        'type' => 'hidden',
        'label' => 'Id родителя',
      ),
      
  //----------------
  'name_max' => 
      array (
        'row' => 1,
        'type' => 'text',
        'label' => 'Полное наименование',
        'required' => true,
      ),
  'name_min' => 
      array (
        'row'   => 0,
        'type'  => 'text',
        'label' => 'Короткое наименование',
      ),
   
  //----------------- 
  'code' => 
      array (
        'row' => 1,
        'type' => 'textarea',
        'label' => 'Пользовательский код',
      ),
      
  'mnemocode' => 
      array (
        'row' => 1,
        'type' => 'text',
        'label' => 'Мнемокод',
      ),
  'note' => 
      array (
        'row' => 1,
        'type' => 'textarea',
        'label' => 'Примечание',
      ),    
      
  //--------------
  'npp' => 
      array (
        'row' => 1,
        'type' => 'text',
        'label' => 'Порядковый номер',
      ),
     
  'markfordel' => 
      array (
        'row' => 0,
        'type' => 'checkbox',
        'label' => 'Метка на удаление',
      ),
  
  'parents_id' => 
      array (
        'row' => 0,
        'type' => 'text',
        'label' => 'parents_id',
        'disabled' => true,
      ),
      
  //------------------    
  'yes_edit' => 
     array (
        'row' => 1,
        'type' => 'checkbox',
        'label' => 'Разрешение на редактирование',
      ),
      
  'yes_del' => 
      array (
        'row' => 0,
        'type' => 'checkbox',
        'label' => 'Разрешение на удаление',
      ),
  'yes_move' => 
      array (
        'row' => 0,
        'type' => 'checkbox',
        'label' => 'Разрешение на перемещение',
      ),    
      
  //-----------------
  'info' => 
      array (
        'row' => 1,
        'type' => 'textarea',
        'label' => 'Info',
      ),
  
  //--скрытые поля 
      
  'code_ob' => 
      array (
        'row' => 1,
        'type' => 'hidden',
        'label' => 'code_ob',
      ),
  
  'level_this' => 
      array (
        'row' => 1,
        'type' => 'hidden',
        'label' => 'level_this',
      ),
  
  
  'id_pict' => 
      array (
        'row' => 1,
        'type' => 'hidden',
        'label' => 'id_pict',
      ),
      
  'yes_force_update' => 
      array (
        'row' => 1,
        'type' => 'hidden',
        'label' => 'yes_force_update',
      ),
  
);


$otList = array (

  'id_guid' => 
      array (
        'row' => 1,
        'type' => 'hidden',
        'label' => 'id_guid',
      ),
  'id_owner_guid' => 
      array (
        'row' => 1,
        'type' => 'hidden',
        'label' => 'Id владельца',
      ),
  'id_var_guid' => 
      array (
        'row' => 1,
        'type' => 'hidden',
        'label' => 'id_var_guid',
      ),
  
  //---------
  'name_max' => 
      array (
        'row' => 1,
        'type' => 'text',
        'label' => 'Полное наименование',
      ),
  'name_min' => 
      array (
        'row' => 0,
        'type' => 'text',
        'label' => 'Короткое наименование',
      ),
      
  'mnemocode' => 
      array (
        'row' => 1,
        'type' => 'text',
        'label' => 'Мнемокод',
      ),    
      
  'code' => 
      array (
        'row' => 1,
        'type' => 'textarea',
        'label' => 'Пользовательский код',
      ),    
      
  'note' => 
      array (
        'row' => 1,
        'type' => 'textarea',
        'label' => 'Примечание',
      ),    
      
  //--------------
  
  'npp' => 
      array (
        'row' => 1,
        'type' => 'text',
        'label' => 'Порядковый номер',
      ),
  
  'markfordel' => 
      array (
        'row' => 0,
        'type' => 'checkbox',
        'label' => 'Метка на удаление',
      ),
  
  'info' => 
      array (
        'row' => 1,
        'type' => 'textarea',
        'label' => 'Info',
      ),
  
  //-------------
  'yes_edit' => 
      array (
        'row' => 1,
        'type' => 'checkbox',
        'label' => 'Разрешение на редактирование',
      ),
  'yes_del' => 
      array (
        'row' => 0,
        'type' => 'checkbox',
        'label' => 'Разрешение на удаление',
      ),
  'yes_move' => 
      array (
        'row' => 0,
        'type' => 'checkbox',
        'label' => 'Разрешение на перемещение',
      ),
  
  //---------------
  'model_info' => 
      array (
        'row' => 1,
        'type' => 'textarea',
        'label' => 'model_info',
      ),
      
  'model2_info' => 
      array (
        'row' => 0,
        'type' => 'textarea',
        'label' => 'model2_info',
      ),
      
 'model_info_l' => 
      array (
        'row' => 1,
        'type' => 'textarea',
        'label' => 'model_info_l',
      ),    
      
  'model2_info_l' => 
      array (
        'row' => 0,
        'type' => 'textarea',
        'label' => 'model2_info_l',
      ),

  //--- Скрытые поля 
  'code_ob' => 
      array (
        'row' => 1,
        'type' => 'hidden',
        'label' => 'code_ob',
      ),
      
  'its_abstract' => 
      array (
        'row' => 1,
        'type' => 'hidden',
        'label' => 'its_abstract',
      ),
  
  'yes_force_update' => 
      array (
        'row' => 1,
        'type' => 'hidden',
        'label' => 'yes_force_update',
      ),
  
  'id_pict' => 
      array (
        'row' => 1,
        'type' => 'hidden',
        'label' => 'id_pict',
      ),
  
  
  'its_dir' => 
      array (
        'row' => 1,
        // 'type' => 'checkbox',
        'type' => 'hidden',
        'label' => 'Справочник / Объект',
      ),
      
  'id_parent_guid_in_tree' => 
      array (
        'row' => 1,
        'type' => 'text',
        'label' => '',
      ),
      
  'id_child_type_guid' => 
      array (
        'row' => 0,
        'type' => 'text',
        'label' => '',
      ),
      
  //-------------
  'its_link_only' => 
      array (
        'row' => 1,
        // 'type' => 'checkbox',
        'type' => 'hidden',
        'label' => '',
      ),
      
  'id_link_guid' => 
      array (
        'row' => 0,
        // 'type' => 'text',
        'type' => 'hidden',
        'label' => '',
      ),
      
  
  //--------------
  'script_name_calc' => 
      array (
        'row' => 1,
        'type' => 'textarea',
        'label' => '',
      ),
  'script_name_calc_l' => 
      array (
        'row'  => 0,
        'type' => 'textarea',
        'label' => '',
      ),
  
  
  'script_sel' => 
      array (
        'row' => 1,
        'type' => 'textarea',
        'label' => '',
      ),
  
  //--------------
  'link_type' => 
      array (
        'row' => 1,
        'type' => 'text',
        'label' => '',
      ),    
      
  'idf_full_text_index' => 
      array (
        'row' => 0,
        'type' => 'text',
        'label' => '',
      ),     
      
  //-----------------
  'yes_full_text_index' => 
      array (
        'row' => 1,
        'type' => 'checkbox',
        'label' => '',
      ),    
      
  'yes_script_sel' => 
      array (
        'row' => 0,
        'type' => 'checkbox',
        'label' => '',
      ),
  'yes_obj_namemax_edit' => 
      array (
        'row' => 0,
        'type' => 'checkbox',
        'label' => '',
      ),

  'yes_obj_note_edit' => 
      array (
        'row' => 0,
        'type' => 'checkbox',
        'label' => '',
      ),
      
  'yes_obj_info_edit' => 
      array (
        'row' => 1,
        'type' => 'checkbox',
        'label' => '',
      ),
      
  'yes_mnemo_edit' => 
      array (
        'row' => 0,
        'type' => 'checkbox',
        'label' => '',
      ),
  
  //----------------
  'yes_obj_namemin_edit' => 
      array (
        'row' => 0,
        'type' => 'checkbox',
        'label' => '',
      ),
      
  'yes_obj_code_edit' => 
      array (
        'row' => 0,
        'type' => 'checkbox',
        'label' => '',
      ),    
  
);

$propsGrp = array (

  'id' => 
      array (
        'date_type' => 'integer',
        'row' => 1,
        'type' => 'hidden',
        'label' => '',
      ),
  'id_guid' => 
      array (
        'date_type' => 'character varying',
        'row' => 1,
        'type' => 'hidden',
        'label' => '',
      ),
  'id_parent_guid' => 
      array (
        'date_type' => 'character varying',
        'row' => 1,
        'type' => 'hidden',
        'label' => '',
      ),
      
  //-----------------
  'name_max' => 
      array (
        'date_type' => 'character varying',
        'row' => 1,
        'type' => 'text',
        'label' => 'Полное наименование',
      ),
  'name_min' => 
      array (
        'date_type' => 'character varying',
        'row' => 0,
        'type' => 'text',
       'label' => 'Короткое наименование',
      ),
      
      
  'mnemocode' => 
      array (
        'date_type' => 'character varying',
        'row' => 1,
        'type' => 'text',
        'label' => 'Мнемокод',
      ),
      
  'note' => 
      array (
        'date_type' => 'character varying',
        'row' => 1,
        'type' => 'textarea',
        'label' => 'Примечание',
      ),
   
  'code' => 
      array (
        'date_type' => 'character varying',
        'row' => 1,
        'type' => 'textarea',
        'label' => 'Пользовательский код',
      ),
      
      
  //---------------
  'npp' => 
      array (
        'date_type' => 'integer',
        'row' => 1,
        'type' => 'text',
        'label' => 'Порядковый номер',
      ),     
  'markfordel' => 
      array (
        'date_type' => 'boolean',
        'row' => 0,
        'type' => 'checkbox',
        'label' => 'Метка на удаление',
      ),    
  'parents_id' => 
      array (
        'date_type' => 'character varying',
        'row' => 0,
        'type' => 'text',
        'label' => 'parents_id',
      ),    
        
  //---------------------
  'yes_edit' => 
      array (
        'date_type' => 'boolean',
        'row' => 1,
        'type' => 'checkbox',
        'label' => 'Разрешение на редактирование',
      ),
  'yes_del' => 
      array (
        'date_type' => 'boolean',
        'row' => 0,
        'type' => 'checkbox',
        'label' => 'Разрешение на удаление',
      ),
  'yes_move' => 
      array (
        'date_type' => 'boolean',
        'row' => 0,
        'type' => 'checkbox',
        'label' => 'Разрешение на перемещение',
      ),
  
  //---------------
  'info' => 
      array (
        'date_type' => 'text',
        'row' => 1,
        'type' => 'textarea',
        'label' => 'Info',
      ),
  
  
  //--- Скрытые поля    
  'code_ob' => 
      array (
        'date_type' => 'character varying',
        'row' => 1,
        'type' => 'hidden',
        'label' => '',
      ),    
      
  'level_this' => 
      array (
        'date_type' => 'integer',
        'row' => 1,
        'type' => 'hidden',
        'label' => '',
      ),
  
  
  'id_pict' => 
      array (
        'date_type' => 'integer',
        'row' => 1,
        'type' => 'hidden',
        'label' => '',
      ),
  'yes_force_update' => 
      array (
        'date_type' => 'boolean',
        'row' => 1,
        'type' => 'hidden',
        'label' => '',
      ),

);

$propsList = array (

      'id_guid' => 
          array (
            'date_type' => 'character varying',
            'row' => 1,
            'type' => 'hidden',
            'label' => '',
          ),
      'id_owner_guid' => 
          array (
            'date_type' => 'character varying',
            'row' => 1,
            'type' => 'hidden',
            'label' => '',
          ),
  
      //-------------- 
      'name_max' => 
          array (
            'date_type' => 'character varying',
            'row' => 1,
            'type' => 'text',
            'label' => 'Полное наименование',
          ),
      'name_min' => 
          array (
            'date_type' => 'character varying',
            'row' => 0,
            'type' => 'text',
            'label' => 'Короткое наименование',
          ),
          
      'note' => 
          array (
            'date_type' => 'character varying',
            'row' => 1,
            'type' => 'textarea',
            'label' => 'Примечание',
          ), 
      
     'mnemocode' => 
          array (
            'date_type' => 'character varying',
            'row' => 1,
            'type' => 'text',
            'label' => 'Мнемокод',
          ),  
          
     //---------- 
     'yes_edit' => 
          array (
            'date_type' => 'boolean',
            'row' => 1,
            'type' => 'checkbox',
            'label' => 'Разрешение на редактирование',
          ),
      'yes_del' => 
          array (
            'date_type' => 'boolean',
            'row' => 0,
            'type' => 'checkbox',
            'label' => 'Разрешение на удаление',
          ),
      'yes_move' => 
          array (
            'date_type' => 'boolean',
            'row' => 0,
            'type' => 'checkbox',
            'label' => 'Разрешение на перемещение',
          ),  
       
      'info' => 
          array (
            'date_type' => 'text',
            'row' => 1,
            'type' => 'textarea',
            'label' => 'Info',
          ),
      'code' => 
          array (
            'date_type' => 'character varying',
            'row' => 1,
            'type' => 'textarea',
            'label' => 'Пользовательский код',
          ),   
        
     //--------------
     'npp' => 
          array (
            'date_type' => 'integer',
            'row' => 1,
            'type' => 'text',
            'label' => 'Порядковый номер',
          ),
      'markfordel' => 
          array (
            'date_type' => 'boolean',
            'row' => 0,
            'type' => 'checkbox',
            'label' => 'Метка на удаление',
          ),
      
      //---------------  
      'prop_type' => 
          array (
            'date_type' => 'integer',
            'row' => 1,
            'type' => 'text',
            'label' => 'Код свойства',
          ),
          
      'pt_name' => 
          array (
            'date_type' => 'character varying',
            'row' => 0,
            'type' => 'text',
            'label' => 'Свойство',
            'disabled' => true,
          ), 
          
          
      //------ Скрытые поля ---
      'yes_force_update' => 
          array (
            'date_type' => 'boolean',
            'row' => 1,
            'type' => 'hidden',
            'label' => '',
          ),

      'code_ob' => 
          array (
            'date_type' => 'character varying',
            'row' => 1,
            'type' => 'hidden',
            'label' => '',
          ),
          
          
      //---------------    
      'id_var_guid' => 
          array (
            'date_type' => 'character varying',
            'row' => 1,
            'type' => 'text',
            'label' => '',
          ),
      
      'id_link_guid' => 
          array (
            'date_type' => 'character varying',
            'row' => 0,
            'type' => 'text',
            'label' => '',
          ),
          
      //--------------    
      'yes_full_text_index' => 
          array (
            'date_type' => 'boolean',
            'row' => 1,
            'type' => 'checkbox',
            'label' => '',
          ),
      'yes_script_sel' => 
          array (
            'date_type' => 'boolean',
            'row' => 0,
            'type' => 'checkbox',
            'label' => '',
          ),
     
      'yes_mnemo_edit' => 
          array (
            'date_type' => 'boolean',
            'row' => 0,
            'type' => 'checkbox',
            'label' => '',
          ),
          
      'its_tree' => 
          array (
            'date_type' => 'boolean',
            'row' => 0,
            'type' => 'checkbox',
            'label' => '',
          ),    
      
      //-----------------    
      'script_sel' => 
          array (
            'date_type' => 'text',
            'row' => 1,
            'type' => 'textarea',
            'label' => '',
          ),     
         
);

return array(

   'ot_grp'     => $otGrp,
   'ot_list'    => $otList,
   'props_grp'  => $propsGrp,
   'props_list' => $propsList,
   
);
