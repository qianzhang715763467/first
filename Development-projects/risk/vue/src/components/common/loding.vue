<template>
	<div id="loding">
		<svg  class="loading" width="100px" height="100px" viewBox="0 0 44 44">
            <circle class="path" fill="none" stroke-width="2" stroke-linecap="round" cx="22" cy="22" r="20"></circle>
        </svg>
	</div>
</template>

<script>
	export default{
		name:"loding",
		data(){
			return{
				
			}
		}
	}
</script>

<style type="text/less" lang="less">

	#loding{
		position: absolute;
		z-index: 100000;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: #fff;
		.loading {
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			margin: auto;
	        animation: rotator 1.4s linear infinite;
	    }
	    @keyframes rotator {
	        0% {
	            transform: scale(0.5) rotate(0deg);
	        }
	        100% {
	            transform: scale(0.5) rotate(270deg);
	        }
	    }
	    .loading .path {
	        stroke: #009dd7;
	        stroke-dasharray: 126;
	        stroke-dashoffset: 0;
	        transform-origin: center;
	        animation: dash 1.4s ease-in-out infinite;
	    }
	    @keyframes dash {
	        0% {
	            stroke-dashoffset: 126;
	        }
	        50% {
	            stroke-dashoffset: 63;
	            transform: rotate(135deg);
	        }
	        100% {
	            stroke-dashoffset: 126;
	            transform: rotate(450deg);
	        }
	    }
	}
</style>