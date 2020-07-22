@yield('popup_start')

  <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      @yield('modal_header')
  </div>

  <div class="modal-body">
      @yield('modal_body')
  </div>

  <div class="modal-footer">
      @yield('modal_footer')
  </div>

@yield('popup_end')