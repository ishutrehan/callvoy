<?php
	$presenter = new Illuminate\Pagination\BootstrapPresenter($paginator);

	$trans = $environment->getTranslator();
?>

<?php if ($paginator->getLastPage() > 1): ?>
	<ul class="pager">
		<?php
		
		if ( $paginator->getCurrentPage() != 1) {
			echo $presenter->getPrevious($trans->trans('pagination.previous')); 
		}
			?>
			
			
			<?php /*
			<span class="currentPage"><?php echo $paginator->getCurrentPage(); ?></span>
			 * 
			 */
			 ?>
			
			<?php 
			
			if ( $paginator->getCurrentPage() != $paginator->getLastPage() ) {
			
			echo $presenter->getNext($trans->trans('pagination.next'));
				
			}
		?>
	</ul>
<?php endif; ?>
