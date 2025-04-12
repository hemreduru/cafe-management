@extends('adminlte::page')

@section('title', 'Bildirim Testleri')

@section('content_header')
    <h1>Bildirim ve Onay Testleri</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">SweetAlert2 Testleri</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h5>Onay İşlemleri</h5>
                        <button class="btn btn-danger" id="testDeleteConfirm">
                            <i class="fas fa-trash"></i> Silme Onayı
                        </button>
                        
                        <button class="btn btn-warning" id="testGeneralConfirm">
                            <i class="fas fa-question-circle"></i> Genel Onay
                        </button>
                    </div>
                    
                    <div class="mb-3">
                        <h5>Bildirimler</h5>
                        <button class="btn btn-success" id="testSuccess">
                            <i class="fas fa-check"></i> Başarı
                        </button>
                        
                        <button class="btn btn-danger" id="testError">
                            <i class="fas fa-times"></i> Hata
                        </button>
                        
                        <button class="btn btn-info" id="testInfo">
                            <i class="fas fa-info-circle"></i> Bilgi
                        </button>
                        
                        <button class="btn btn-warning" id="testWarning">
                            <i class="fas fa-exclamation-triangle"></i> Uyarı
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Toastr Testleri</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <button class="btn btn-success" id="toastrSuccess">
                            <i class="fas fa-check"></i> Başarı
                        </button>
                        
                        <button class="btn btn-danger" id="toastrError">
                            <i class="fas fa-times"></i> Hata
                        </button>
                        
                        <button class="btn btn-info" id="toastrInfo">
                            <i class="fas fa-info-circle"></i> Bilgi
                        </button>
                        
                        <button class="btn btn-warning" id="toastrWarning">
                            <i class="fas fa-exclamation-triangle"></i> Uyarı
                        </button>
                    </div>
                    
                    <div class="mb-3">
                        <button class="btn btn-secondary" id="toastrClear">
                            <i class="fas fa-broom"></i> Bildirimleri Temizle
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gerçek Formlar</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h5>Form Onayları</h5>
                        <form action="#" method="POST" class="d-inline" data-confirm="delete">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Form ile Silme Onayı
                            </button>
                        </form>
                        
                        <form action="#" method="POST" class="d-inline" data-confirm="yes" data-message="Özel bir onay mesajı">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-check"></i> Form ile Genel Onay
                            </button>
                        </form>
                    </div>
                    
                    <div class="mb-3">
                        <h5>Butonlar ve Linkler</h5>
                        <a href="#" data-confirm="yes" data-title="Bağlantıya gitmek istiyor musunuz?" class="btn btn-primary">
                            <i class="fas fa-link"></i> Onay ile Link
                        </a>
                        
                        <button type="button" data-confirm="yes" data-icon="warning" data-message="Bu önemli bir buton!" class="btn btn-secondary">
                            <i class="fas fa-bell"></i> Onay ile Buton
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // SweetAlert2 Testleri
        document.getElementById('testDeleteConfirm').addEventListener('click', function() {
            SwalHandler.confirmDelete(
                function() { 
                    ToastHandler.success('Silme işlemi başarıyla gerçekleşti!');
                }
            );
        });
        
        document.getElementById('testGeneralConfirm').addEventListener('click', function() {
            SwalHandler.confirm(
                function() { 
                    ToastHandler.info('İşlem onaylandı!');
                },
                {
                    title: 'Özel Onay Başlığı',
                    text: 'Özel onay mesajı burada olacak. Devam etmek istiyor musunuz?',
                    icon: 'question'
                }
            );
        });
        
        document.getElementById('testSuccess').addEventListener('click', function() {
            SwalHandler.success('Başarılı İşlem', 'İşleminiz başarıyla tamamlandı.');
        });
        
        document.getElementById('testError').addEventListener('click', function() {
            SwalHandler.error('Hata', 'İşlem sırasında bir hata oluştu.');
        });
        
        document.getElementById('testInfo').addEventListener('click', function() {
            SwalHandler.info('Bilgi', 'Bu bir bilgi mesajıdır.');
        });
        
        document.getElementById('testWarning').addEventListener('click', function() {
            SwalHandler.warning('Uyarı', 'Bu bir uyarı mesajıdır.');
        });
        
        // Toastr Testleri
        document.getElementById('toastrSuccess').addEventListener('click', function() {
            ToastHandler.success('İşlem başarıyla tamamlandı.', 'Başarılı');
        });
        
        document.getElementById('toastrError').addEventListener('click', function() {
            ToastHandler.error('İşlem sırasında bir hata oluştu.', 'Hata');
        });
        
        document.getElementById('toastrInfo').addEventListener('click', function() {
            ToastHandler.info('Bu bir bilgi mesajıdır.', 'Bilgi');
        });
        
        document.getElementById('toastrWarning').addEventListener('click', function() {
            ToastHandler.warning('Bu bir uyarı mesajıdır.', 'Uyarı');
        });
        
        document.getElementById('toastrClear').addEventListener('click', function() {
            ToastHandler.clear();
        });
    });
</script>
@stop