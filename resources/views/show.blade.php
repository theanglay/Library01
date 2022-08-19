<?php
    $id = isset($id) ? $id : 0;
    $saledetail_data = \App\Models\Saledetail::where('sale_id', $id)->get();
?>

<table class="table table-bordered">
    <thead>
    <th>Name</th>
    <th>Price</th>
    <th>Qty</th>
    <th>LineAmt</th>
    <th>Action</th>
    </thead>
    <tbody id="prodadd">
    @if($saledetail_data != null)
        @foreach($saledetail_data as $item)
            @include('edit_prod_table', $item)
        @endforeach
    @endif

    </tbody>
</table>
@push('crud_fields_scripts')
    <script>
        $(function (){
            // select product
            $('body').on('change', '[name="book_id"]', function (){
                var prod_id = $(this).val()-0;

                $.ajax({
                    type: 'GET',
                    url: ' {{url('admin/showborrow')}}' + '/' + prod_id,
                    success: function(d){
                        $('#prodadd').append(d);

                        total_();

                    },
                    error: function (d){
                        // alert('Error')
                    }
                })
            });

            // remove row product
            $('body').on('click', '.remove_detail', function (){
                var row_id =$(this).data('row_id');
                var tr = ($('#r-'+row_id));

                tr.remove();
                total_line(tr);

            });

            // edit qty product row
            $('body').on('keyup', '.qty', function (){
                var r_id = $(this).data('row_id');
                var tr = $('#r-'+r_id);

                total_line(tr);
            });

            //edit price product row
            $('body').on('keyup', '.price', function (){
                var r_id = $(this).data('row_id');
                var tr = $('#r-'+r_id);

                total_line(tr);
            });

            // total price &qty
            function total_(){
                var total_lnamt = 0
                var total_qty = 0

                $('.lineamt').each(function (){
                    var amount = $(this).val()-0

                      if(amount > 0) {
                         total_lnamt += amount;
                      }
                    })

                $('.qty').each(function (){
                    var qty = $(this).val()-0

                    if(qty > 0) {
                        total_qty += qty;
                    }
                })

                $('[name="total_price"]').val(total_lnamt);
                $('[name="total_qty"]').val(total_qty);
                $('[name="product"]').val(null);
            }

            //find qty & price & calculate line amount
            function total_line(tr){


                var price = tr.find('.price').val()-0;

                var lnamt = qty * price;

                tr.find('.lineamt').val(lnamt);
                total_();

            }
        });
    </script>
@endpush


