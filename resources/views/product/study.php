    <!-- form -->
    <div class="col-3">
        <input type="text" name="part_list" id="part_list" class="form-control form-control-sm" placeholder="Search for Item name or sku" autocomplete="off" autofocus>
    </div>
    <!-- column  -->
    <div class="col-12">
        <div id="itemLists"></div>
    </div>
    <!-- include this   {{ csrf_field() }} -->


    <!-- consider this a query for autocomplete -->
    <script>

        $(document).mouseup(function(e){
            var container = $("#itemLists");
            if (!container.is(e.target) && container.has(e.target).length === 0){
                container.hide();
                $("#part_list").val('');
            }
        });
        
        $(document).ready(function(){
            $('#part_list').keyup(function(){
                var query = $(this).val();
                if(query != ''){
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('autocomplete') }}",
                        method:"POST",
                        data:{query:query , _token:_token},
                        success:function(data){
                            $('#itemLists').fadeIn();
                            $('#itemLists').html(data);
                        }
                    });
                }
            });
        });

        $(document).ready(function(){
            $('#area_name').keyup(function(){
                var query = $(this).val();
                if(query != ''){
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('area.autocomplete') }}",
                        method:"POST",
                        data:{query:query , _token:_token},
                        success:function(data){
                            $('#areaList').fadeIn();
                            $('#areaList').html(data);
                        }
                    });
                }
            });
        });
    </script>

    <!-- Controller -->
    <?php
        function autocomplete(Request $request)
        {
            if($request->get('query')) {
                $query = $request->get('query');
                $output = '';
                $data = DB::table('parts_lists')
                    ->leftJoin('brand_tbls', 'parts_lists.b_id', '=', 'brand_tbls.id')
                    ->where('details' ,'LIKE', "%{$query}%")
                    ->orWhere('sku' ,'LIKE', "%{$query}%")
                    ->get();
                if(count($data)>0) {
                    $output .= '<table class=" table mt-3">
                        <tr>
                            <th>Name</th>
                            <th>Sku</th>
                            <th>Part #</th>
                            <th>Brand</th>
                        </tr>
                    </div>';
                    foreach ($data as $row) {
                        $output .= '<tbody>
                            <tr>
                                <td><a href="'.route('add.edit', $row->sku).'">'.ucwords($row->details).'</td>
                                <td>ghecc'.$row->sku.'</a></td>
                                <td>'.$row->partno.'</td>
                                <td>'.ucwords($row->brand).'</td>
                            </tr>
                        </tbody>';
                    }
                }else{
                    $output .= '<hr>
                    <div class="text-center">Item Not Found</div>
                    <hr>';
                }
                $output .= '</div>';
                return $output;
                return view('add.add_quantity');
            }
        }
    ?>
                            // <td><a href="'.route('add.edit', $row->prod_sku).'">'.ucwords($row->prod_name).'</td>
