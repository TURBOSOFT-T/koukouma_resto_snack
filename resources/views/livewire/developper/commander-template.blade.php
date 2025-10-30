<div class="card p-3">
    <div class="text-center">
        <h4>
            <b>
                للطلب، املأ المعلومات أدناه
            </b>
        </h4>
    </div>
    <br>


        <form wire:submit="save">
            <table class="w-100">
                <tr>
                    <td>
                        <b>
                            الاسم الكامل
                            <b class="text-danger">*</b>
                        </b>
                    </td>
                    <td>
                        <div class="input-group  mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">
                                    <i class="bi bi-person-circle"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" wire:model="nom" placeholder="Mohamed" required aria-label="Small"
                                aria-describedby="inputGroup-sizing-sm">
                        </div>
                        @error('nom')
                            <span class="small text-danger"> {{ $message }} </span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>
                            رقم الهاتف
                            <b class="text-danger">*</b>
                        </b>
                    </td>
                    <td>
                        <div class="input-group  mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">
                                    <i class="bi bi-telephone-fill"></i>
                                </span>
                            </div>
                            <input type="text" required class="form-control @error('telephone') is-invalid @enderror" placeholder="+xxx xx xxx xxx" wire:model="telephone" required minlength="8" aria-label="Small"
                                aria-describedby="inputGroup-sizing-sm">
                        </div>
                        @error('telephone')
                            <span class="small text-danger"> {{ $message }} </span>
                        @enderror
                    </td>
                </tr>
            </table>
            <div class="text-centter">
                <button class="btn btn-primary btn-block" type="submit">
                    <span wire:loading>
                        <img src="https://i.gifer.com/ZKZg.gif" width="20" height="20" class="rounded shadow"
                            alt="">
                    </span>
                    <i class="bi bi-bag-check"></i>
                    <b>
                        اطلب الان!
                    </b>
                </button>
            </div>
        </form>
</div>
