<div>
    <div class="card my-4">
        <div class="card-header pb-0">
          <div class="row align-items-center justify-content-between">
            <div class="col-auto">
              <h6 class="mb-0">Payment Summary</h6>
            </div>
            <div class="col-auto">
                <div class="row justify-content-between">
                    @if(session()->has('error'))
                      <div class="col-auto alert alert-danger mt-3">
                          {{ session('error') }}
                      </div>
                  @endif
                <div class="col-auto">
                  <label class="form-label text-uppercase small mb-1">Export Type</label>
                  <select 
                    wire:model="export_type" 
                    class="form-select border border-2 p-2 custom-input-sm" 
                    wire:change="updateFilters('export_type', $event.target.value)">
                    <option value="" selected hidden>Select type</option>
                    <option value="deposit">Export For Deposit Amount</option>
                    <option value="rental">Export For Rental Amount</option>
                    <option value="all">Export For All</option>
                  </select>
                </div>
                </div>
            </div>
          </div>

          <div class="row mb-3 g-2 align-items-end">
            
            <!-- Rider Filter -->
            <div class="col-md-2">
              <label for="rider" class="form-label text-uppercase small">Select Riders</label>
              <select id="rider" wire:model="selected_rider" wire:change="updateFilters('selected_rider', $event.target.value)" class="form-select border border-2 p-2 custom-input-sm">
                <option value="" selected hidden>Select Rider</option>
                  @foreach ($filterData['rider'] as $rider)
                    <option value="{{$rider['id']}}">{{$rider['name']}}</option>
                  @endforeach
              </select>
            </div>
      
            <!-- Product Type -->
            <div class="col-md-2">
              <label class="form-label text-uppercase small">Product Type</label>
              <select wire:model="selected_product_type" class="form-select border border-2 p-2 custom-input-sm"  wire:change="updateFilters('selected_product_type', $event.target.value)">
                <option value="" selected hidden>Select type</option>
                  @foreach ($filterData['product_type'] as $product_type)
                    <option value="{{$product_type}}">{{ucwords(str_replace('_', ' ', $product_type))}}</option>
                  @endforeach
              </select>
            </div>
      
            <!-- Payment Status -->
            <div class="col-md-2">
              <label class="form-label text-uppercase small">Payment Status</label>
              <select wire:model="selected_payment_status" class="form-select border border-2 p-2 custom-input-sm" wire:change="updateFilters('selected_payment_status',$event.target.value)">
                <option value="" selected hidden>Select Status</option>
                  @foreach ($filterData['payment_status'] as $payment_status)
                    <option value="{{$payment_status}}">{{$payment_status=="completed"?"Captured":ucwords($payment_status)}}</option>
                  @endforeach
              </select>
            </div>
      
            <!-- Start Date -->
            <div class="col-md-2">
              <label class="form-label text-uppercase small">Start Date</label>
              <input type="date" wire:model="start_date" wire:change="updateFilters('start_date', $event.target.value)" class="border border-2 p-2 custom-input-sm form-control">
            </div>
      
            <!-- End Date -->
            <div class="col-md-2">
              <label class="form-label text-uppercase small">End Date</label>
              <input type="date" wire:model="end_date" wire:change="updateFilters('end_date', $event.target.value)" class="border border-2 p-2 custom-input-sm form-control">
            </div>
            <div class="col-md-1">
              <a href="javascript:void(0)"
                class="btn btn-danger text-white custom-input-sm" wire:click="resetPageField">
                <i class="ri-restart-line"></i>
              </a>
            </div>
            <!-- Export Button -->
            <div class="col-md-1 d-grid">
              <button wire:click="exportAll" class="btn btn-primary mt-3">
                <i class="ri-download-line"></i> Export
              </button>
            </div>
          </div>
        </div>
      
        <div class="card-body px-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0 product-list">
              <thead>
                <tr>
                  <th>SL</th>
                  <th>Rider Name / Mobile</th>
                  <th>Product Type</th>
                  <th class="text-center">Vehicle Type</th>
                  <th class="text-center">Amount</th>
                  <th>Transaction ID</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Payment Date</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($data as $key=> $item)
                  @php
                      $colors = ['bg-label-primary', 'bg-label-success', 'bg-label-info', 'bg-label-secondary', 'bg-label-danger', 'bg-label-warning'];
                      $colorClass = $colors[$key % count($colors)]; // Rotate colors based on index
                  @endphp
                  <tr>
                    <td rowspan="@if(in_array($key, $expandedRows)) 2 @else 1 @endif">{{$key+1}}</td>
                    <td>
                        @if($item->user)
                          <div class="d-flex justify-content-start align-items-center customer-name">
                            <div class="avatar-wrapper me-3">
                                <div class="avatar avatar-sm">
                                    @if ($item->user->image)
                                        <img src="{{ asset($item->user->image) }}" alt="Avatar" class="rounded-circle">
                                    @else
                                        <div class="avatar-initial rounded-circle {{$colorClass}}">
                                            {{ strtoupper(substr($item->user->name, 0, 1)) }}{{ strtoupper(substr(strrchr($item->user->name, ' '), 1, 1)) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <a href="{{ route('admin.customer.details', $item->user->id) }}"
                                    class="text-heading"><span class="fw-medium text-truncate">{{ ucwords($item->user->name) }}</span>
                                </a>
                                <small class="text-truncate">{{ $item->user->country_code }}{{ $item->user->mobile}} </small>
                            <div>
                          </div>
                        @else
                          N/A
                        @endif
                    </td>
                    <td>{{ucwords(str_replace('_', ' ', $item->order_type))}}</td>
                    <td class="text-center">{{ optional($item->order->product)->title ?? 'N/A' }}</td>
                    <td class="text-center">{{ENV('APP_CURRENCY')}}{{$item->amount}}</td>
                    <td>{{$item->icici_txnID}}</td>
                    <td class="text-center">
                      @if($item->payment_status=="completed")
                        <span class="badge bg-success">Captured</span>
                      @else
                        <span class="badge bg-danger">{{ucwords($item->payment_status)}}</span>
                      @endif
                    </td>
                    <td class="text-center">{{ date('d M y h:i A', strtotime($item->payment_date)) }}</td>
                    <td class="text-center">
                      @if($item->icici_merchantTxnNo)
                        <a href="javascript:void(0)" wire:click="toggleRow({{ $key }}, '{{$item->icici_merchantTxnNo}}',{{$item->amount}})">
                          <span class="control"></span>
                        </a>
                        @endif
                      </td>
                  </tr>
                    @if(in_array($key, $expandedRows))
                     <tr>
                      <td colspan="8" style="background: aliceblue;">
                          @if(isset($transaction_details[$key]['status']) && $transaction_details[$key]['status'] === false)
                              <p>Error: {{ $transaction_details[$key]['message'] }}</p>
                          @else
                              <div>
                                  <strong>Transaction ID:</strong> {{ $transaction_details[$key]['txnID'] ?? 'N/A' }}<br>
                                  <strong>Merchant Txn No:</strong> {{ $transaction_details[$key]['merchantTxnNo'] ?? 'N/A' }}<br>
                                  <strong>Amount:</strong> {{ env('APP_CURRENCY') }}{{ number_format($transaction_details[$key]['amount'], 2) }}<br>
                                  <strong>Status:</strong> 
                                    @if($transaction_details[$key]['txnStatus'] == 'SUC')
                                      Captured
                                    @else
                                      Failed
                                    @endif
                                  <br>
                                  <strong>Payment Mode:</strong> {{ $transaction_details[$key]['paymentMode'] ?? 'N/A' }}<br>
                                  <strong>Bank:</strong> {{ $transaction_details[$key]['paymentSubInstType'] ?? 'N/A' }}<br>
                                  <strong>Auth ID:</strong> {{ $transaction_details[$key]['txnAuthID'] ?? 'N/A' }}<br>
                                  <strong>Email:</strong> {{ $transaction_details[$key]['customerEmailID'] ?? 'N/A' }}<br>
                                  <strong>Contact:</strong> {{ $transaction_details[$key]['customerMobileNo'] ?? 'N/A' }}<br>
                                  <strong>Transaction Time:</strong> 
                                    {{ \Carbon\Carbon::createFromFormat('YmdHis', $transaction_details[$key]['paymentDateTime'])->format('d M Y, h:i:s A') ?? 'N/A' }}
                                  <br>
                                  <strong>Transaction Status Code:</strong> {{ $transaction_details[$key]['txnResponseCode'] ?? 'N/A' }}<br>
                                  <strong>Response Description:</strong> {{ $transaction_details[$key]['txnRespDescription'] ?? 'N/A' }}<br>
                                </div>

                          @endif
                      </td>
                  </tr>
                    @endif
                @empty
                    <tr>
                      <td colspan="9">
                          <div class="alert alert-danger">
                            Sorry! data not found!
                        </div>
                      </td>
                    </tr>
                @endforelse
                  
              </tbody>
            </table>
            <div class="d-flex justify-content-end mt-3 paginator">
              {{ $data->links('pagination::bootstrap-4') }}
          </div>
          </div>
        </div>
      </div>
      <div class="loader-container" wire:loading>
        <div class="loader"></div>
    </div>
</div>
