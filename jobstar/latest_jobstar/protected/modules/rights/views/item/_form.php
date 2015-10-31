<?php

<div class="row">
        <?php echo $form->labelEx($model, 'Category_id'); ?>
        <div id='parent_category_name' style='color:red;'><?php echo ($model->category) ? $model->category->name : '-'; ?></div>
        <?php
        $dataTree = Category::getTree();
 
        $this->widget('CTreeView', array(
            'id' => 'category-treeview',
            'data' => array(array('text' => CHtml::link('Αρχική', '#', array('id' => null)), 'children' => $dataTree)),
            'collapsed' => false,
            'htmlOptions' => array(
                'class' => 'treeview-famfamfam',
            ),
        ));
        ?>
        <?php echo $form->hiddenField($model, 'parent_category_Id'); ?>
        <?php echo $form->error($model, 'parent_category_Id'); ?>
    </div>
 
 
 
    <script>
        $('#category-treeview a').live('click', function() {
            $('#parent_category_name').html($(this).html());
            $('#item_category_id').val($(this).attr('id'));
            return false;
        });
    </script>

    ?>