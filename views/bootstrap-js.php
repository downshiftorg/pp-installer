<script>
window.ppi = {
  token: '<?php echo $token; ?>',
  links: {
    save_option: '<?php echo $ajaxUrl ?>?action=ppi_api&affordance=save_option',
    get_registration: 'https://api.prophoto.com/token/<?php echo $token ?>',
    install_p6: '<?php echo $ajaxUrl ?>?action=ppi_api&affordance=install_p6'
  }
};
</script>
