<!--
{{ $ex }}
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $ex->getMessage() }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/Lemon-Framework/static@master/dist/reporter/app.css">
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Source+Code+Pro:wght@200&display=swap');
        * {
            font-family: 'Source Code Pro', monospace;
        }
    </style>
</head>
<body class="bg-darkgray text-lightgray">
<div id="app" class="invisible block mx-auto sm:visible 2xl:w-1/2">
    <header class="flex flex-col py-16 pl-5">
        <div class="flex content-center justify-between">
            <span class="mb-3 text-2xl">{{ get_class($ex) }}</span>
            <button class="p-2 mr-5 bg-midgray hover:bg-darkgray" v-on:click="toggleVendor()">Toggle vendor</button>
        </div>
        <span class="text-4xl">{{ $ex->getMessage() }}</span>
        <span class="mt-3 text-1xl">In file {{ $ex->getFile() }} on line {{ $ex->getLine() }}</span>
    </header>
    <trace></trace>
</div>
</body>

<script>
let trace = {! $contents !}
</script>

<script src="https://cdn.jsdelivr.net/gh/Lemon-Framework/static@master/dist/reporter/app.js"></script>
</html>
