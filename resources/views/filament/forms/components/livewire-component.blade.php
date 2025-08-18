<x-dynamic-component 
    :component="$getLivewireComponent()" 
    :record="$getRecord()"
    @isset($getLivewireParams()['record'])
        :record="$getLivewireParams()['record']"
    @endisset
/>