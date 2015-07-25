Seras redireccionado en <b id="counter"></b> segundos.

<script type="text/javascript">

	var url = "<?php echo $url ?>";
	var limit   = <?php echo $delay ?>;
	var counter = document.getElementById("counter");

	counter.innerHTML = limit;

	setInterval(function(){
		limit--;

		if(limit <= 0) {
			window.location = url;
		}

		counter.innerHTML = limit;
	}, 1000)

</script>