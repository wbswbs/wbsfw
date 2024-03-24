{*
 Anzeige der Log Einträge
 *}
<style>

    table#tbl_it_admin{
    }

    table#tbl_it_admin tr.level_INFO td{
        /*background-color: #7fbb23;*/
        /*color: #fff;*/
    }
    table#tbl_it_admin tr.level_TEST td{
        background-color: lightblue;
        color: #000;
    }
    table#tbl_it_admin tr.level_DEBUG td{
        background-color: orange;
        color: #fff;
    }
    table#tbl_it_admin tr.level_WARNING td{
        background-color: darkblue;
        color: #fff;
    }
    table#tbl_it_admin tr.level_CRITICAL td{
        background-color: mediumpurple;
        color: #fff;
    }
    table#tbl_it_admin tr.level_ERROR td{
        background-color: palevioletred;
        color: #fff;
    }

    table#tbl_it_admin tr td{
        padding: 2px 5px;
        border-right:1px dotted #ccc;
        border-bottom:1px dotted #ccc;
    }

    /*td.log_message{*/
{*        text-overflow: ellipsis;*}
/*        background: lightyellow;*/
/*        display: block;*/
/*    }*/
    div.item_row {
        border-bottom: 1px dotted #ccc;
        display: flex;
        flex-direction: row;
    }

    div.item_row label {
        width: 250px;
        text-overflow: ellipsis;
        padding-right: 5px;
    }
</style>

<h2>Log Einträge aus der Tabelle {$log_tabelle}</h2>

{if $msg}
    <div class="alert alert-info">{$msg}</div>{/if}

<table id="tbl_it_admin" data-page-length="50">
    <thead>
    <tr>
        <th>Created</th>
        <th>Level</th>
        <th>Projekt</th>
        <th>Message</th>
        <th>User</th>
        <th>IP</th>
        <th>Controller</th>
        <th>Action</th>
        <th>AuftragNR</th>
        <th>AuftragID</th>
        <th>PosID</th>
    </tr>
    </thead>
    <tbody>

    {foreach $list as $wbs_order}
        <tr>
           <td>Dummy</td>
           <td>Dummy</td>
           <td>Dummy</td>
           <td>Dummy</td>
           <td>Dummy</td>
           <td>Dummy</td>
           <td>Dummy</td>
           <td>Dummy</td>
           <td>Dummy</td>
           <td>Dummy</td>
           <td>Dummy</td>
        </tr>
    {/foreach}
    </tbody>

</table>

    <script>
        if(typeof wbsOrderConfig === "undefined") {
            // Variablen von außerhalb
            let wbsOrderConfig = {
                link_zum_auftragszentrum: '{$link_zum_auftragszentrum}',
                ajax_link: '{$ajax_link}'
            }
        }
    </script>

{literal}

    <script src="/js/data_tables/datatables.js"></script>
    <script src="/js/data_tables_ui/DataTables_1_10_23/js/dataTables.bootstrap.js"></script>
    <link rel="stylesheet" href="/js/data_tables_ui/DataTables_1_10_23/css/dataTables.bootstrap.css">
    <script>
        /**
         *
         * @param cutoff Zeichen die bleiben
         * @param wordbreak bool
         * @param escapeHtml
         * @returns {(function(*=, *, *): (*|string))|*}
         */
        jQuery.fn.dataTable.render.ellipsis = function ( cutoff, wordbreak, escapeHtml ) {
            var esc = function ( t ) {
                return t
                    .replace( /&/g, '&amp;' )
                    .replace( /</g, '&lt;' )
                    .replace( />/g, '&gt;' )
                    .replace( /"/g, '&quot;' );
            };

            return function ( d, type, row ) {
                // Order, search and type get the original data
                if ( type !== 'display' ) {
                    return d;
                }

                if ( typeof d !== 'number' && typeof d !== 'string' ) {
                    return d;
                }

                d = d.toString(); // cast numbers

                if ( d.length <= cutoff ) {
                    return d;
                }

                var shortened = d.substr(0, cutoff-1);

                // Find the last white space character in the string
                if ( wordbreak ) {
                    shortened = shortened.replace(/\s([^\s]*)$/, '');
                }

                // Protect against uncontrolled HTML input
                if ( escapeHtml ) {
                    shortened = esc( shortened );
                }

                return '<span class="ellipsis" ' +
                 ' onclick="Station.showTemporaryModalMessage(this.title,5000);return false" title="'+esc(d)+'">' +
                    shortened+'&#8230;</span>';

                // return '<span class="ellipsis" ' +
                //  ' onclick="window.alert(this.title);return false" title="'+esc(d)+'">' +
                //     shortened+'&#8230;</span>';
            };
        };
        $(document).ready(function () {
            $('#tbl_it_admin').DataTable(
                {
                    bSort: true,
                    language: {
                        url: '../js/data_tables/german.language.json'
                    },
                    /**
                     * Manipulation eines Ergebnisses
                    columnDefs: [ {
                        targets: 3,
                        // See https://datatables.net/plug-ins/dataRender/ellipsis
                        render: $.fn.dataTable.render.ellipsis( 30, true )
                    } ],
                     */
                    aoColumns: [
                        {sWidth: "10%", bSearchable: true, bSortable: true}, // Created
                        {sWidth: "5%", sClass:"level", bSearchable: true, bSortable: true}, // Level
                        {sWidth: "5%", bSearchable: true, bSortable: true}, // Project
                        {sWidth: "45%", sClass: "log_message", bSearchable: true, bSortable: true}, // Message
                        {sWidth: "5%", bSearchable: true, bSortable: true}, // User
                        {sWidth: "5%", bSearchable: true, bSortable: true}, // IP
                        {sWidth: "5%", bSearchable: true, bSortable: true}, // Controller
                        {sWidth: "5%", bSearchable: true, bSortable: true}, // Action
                        {sWidth: "5%", bSearchable: true, bSortable: true}, // AuftragNr
                        {sWidth: "5%", bSearchable: true, bSortable: true, type:"num"}, // AuftragID
                        {sWidth: "5%", bSearchable: true, bSortable: true, type:"num"} // PositionsID
                    ],
                    scrollY: "500px",
                    // "scrollCollapse": false,
                    info: true,
                    // "paging": true,
                    processing: true,
                    serverSide: true,
                    order: [[ 0, 'desc' ], [ 1, 'desc' ]],
                    ajax: wbsOrderConfig.ajax_link,
                    // Start the Search immediatly (default = 400ms)
                    //searchDelay: 0
                    createdRow: function(row, data, index) {
                        // console.log(data);
                        $(row).addClass('level_' + data[1]);
                    }
                });
            window.setTimeout(
                function(){
                    $('div#tbl_it_admin_filter input').focus();
                },1500
            );
        });

        if(typeof Auftrag === "undefined") {
            let Auftrag = {
                openAuftragsId: function (auftrags_id) {
                    console.log('Auftrag.openAuftragsId(' + auftrags_id + ')');
                    window.open(wbsOrderConfig.link_zum_auftragszentrum + auftrags_id, '_blank');
                },
                openPositionsId: function (positions_id) {
                    console.log('Auftrag.openPositionsId(' + positions_id + ')');
                    document.location.href = 'position.php?function=show_wbs_order&positions_id=' + positions_id;
                    // window.open('position.php?function=show_wbs_order&positions_id=' + positions_id);
                },
                openScansOfPositionsId: function (positions_id) {
                    console.log('Auftrag.openScansOfPositionsId(' + positions_id + ')');
                    document.location.href = 'scans.php?function=showAllScansOfPosition&positions_id=' + positions_id;
                    // window.open('position.php?function=show_wbs_order&positions_id=' + positions_id);
                }
            };
        }
    </script>
{/literal}
