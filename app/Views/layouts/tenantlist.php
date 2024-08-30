<select class="custom-select form-select custom-select-sm" class="custom-select custom-select-sm" aria-label="Default select example" name="tenant" id="tenantchange">
<?php foreach($getdashData['getTenantdata'] as $getTenantlist) { ?> 
<option value="<?php echo $getTenantlist['tenant_id'] ; ?>"><?php echo $getTenantlist['tenant_name'] ; ?></option>
<?php  } ?>
</select>

<script type="text/javascript">
$("#").change(function(){
});
</script>