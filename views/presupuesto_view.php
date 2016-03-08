<?php
      echo tagcontent('div','', array('id'=>'proyectos_crud'));
?>
<script>
    $(document).ready(function(){
        $.ajax({
            type: "POST",
            url: "presupuesto/get_crud",
            dataType: 'html',
//            data: {id_medico: datum.id_doc},
            success: function (proyectos_crud) {
                $('#proyectos_crud').html(proyectos_crud);
        }});
    });
</script>