@section('title', 'View Vote List')
<x-cast-vote-layout>
    <livewire:voting.view-vote-ballot :record="$record"/>

    <script>
        function printOut(data) {
          var mywindow = window.open('', '', 'height=1000,width=1000');
          mywindow.document.write('<html><head>');
          mywindow.document.write('<title></title>');
          mywindow.document.write(`<link rel="stylesheet" href="{{ Vite::asset('resources/css/app.css') }}" />`);
          mywindow.document.write('<style>.print-button { display: none; }</style>'); // Add CSS to hide the button
          mywindow.document.write('</head><body >');
          mywindow.document.write(data);
          mywindow.document.write('</body></html>');

          mywindow.document.close();
          mywindow.focus();
          setTimeout(() => {
            mywindow.print();
            return true;
          }, 1000);
        }
      </script>
</x-cast-vote-layout>
