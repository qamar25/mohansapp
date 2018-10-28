<template>
	<div class="container">
		<div class="jumbotron">
			<h1 class="display-4" v-once>{{siteName}}</h1>
			
		</div>
	</div>
</template>

<script>
	import {siteName} from './../../config';

	export default {
		data() {
			return {
				siteName: siteName
			}
		}
	}
</script>
