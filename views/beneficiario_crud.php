<?php
echo '<h3>Gestion de Beneficiarios</h3>';
echo tagcontent('div','', array('id'=>'proyectos_crud'));
?>
<script>
    $(document).ready(function(){
        $.ajax({
            type: "POST",
            url: "get_crud",
            dataType: 'html',
            success: function (proyectos_crud) {
                $('#proyectos_crud').html(proyectos_crud);
        }});
    });
</script>