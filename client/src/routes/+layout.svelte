<script lang="ts">
	import '../app.css';
	
	let { children } = $props();
</script>

<div class="p-6">
    {@render children()}
</div>
