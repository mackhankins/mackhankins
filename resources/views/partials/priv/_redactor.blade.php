<script src="{{ elixir('js/redactor.js') }}"></script>
<script type="text/javascript">
    jQuery(function () {
        jQuery('#excerpt').redactor({
            maxHeight: 1000,
            focus: true,
            imageUpload: '{!! action("Admin\IndexController@upload") !!}?_token=' + '{{ csrf_token() }}',
            imageManagerJson: '{!! $filesjson !!}',
            plugins: ['table', 'imagemanager'],
            formattingAdd: [
                {
                    tag: 'code',
                    title: 'Inline Code'
                }
            ]
        });
    });
</script>