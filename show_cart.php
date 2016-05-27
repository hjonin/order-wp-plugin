<?php
class Order_Show_Cart
{
    public function __construct()
    {
        add_shortcode('order_show_cart', array($this, 'show_cart_html'));
    }
    
    public function show_cart_html($atts, $content)
    {
?>
<form id="devis_form" action="" method="post">
 <div id="devis_footer_div">
  <button id="devis_footer_button" class="devis-button" type="button">
   <span class="itemCount"></span> item(s) (<span class="itemTotalPrice"></span>) ^
  </button>
  <input id="devis_send_button" class="devis-button" type="submit" value="Envoyer">
 </div>
 <div id="devis_div">
  <h2>Nous contacter pour un devis</h2>
  
  <label for="devis_name">Nom et prénom</label>
  <input id="devis_name" name="devis_name" type="text" required="required"/><br />
  
  <label for="devis_email">E-mail</label>
  <input id="devis_email" name="devis_email" type="email" required="required"/><br />
  
  <label for="devis_summary">Récapitulatif de la demande</label>
  <div id="devis_summary">
   <table>
    <thead>
     <tr>
      <th>Produit</th>
      <th>Qté</th>
      <th>Tot. (€)</th>
     </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
     <tr>
      <td colspan="2"></td>
      <td></td>
     </tr>
    </tfoot>
   </table>
  </div>
  
  <label for="devis_notes">Commentaires</label>
  <textarea id="devis_notes" name="devis_notes" rows="5"></textarea>
  
  <label for="devis_date">Date</label>
  <input id="devis_date" name="devis_date" required="required"/><br />
  
  <input id="devis_copy" name="devis_copy" type="checkbox" checked/><label for="devis_copy" class="pls">M'envoyer une copie de l'E-mail</label>
  
  <input id="action" type="hidden" name="action" value="devis" />
  <?php wp_nonce_field('ajax_devis_nonce', 'security'); ?>
  
 </div>
</form>

<div id="devis_notification"></div>

<!-- set javascript ajaxUrl value when user is on traiteur page !-->
<?php wp_localize_script('order', 'ajaxUrl', admin_url('admin-ajax.php')); ?>

<?php
    }

}
?>