var role = "<?= isset($role) ? $role : '' ?>";
var dtid = "<?= $id ?>";
configTabel[dtid] = [
    { 
        data: '',
        mData: null,
    },
    {
        data: 'nama',
        mRender: function (_, type, row) {
            var nama = row.nama ? row.nama : 'Belum ada nama';
            return nama;
        }
    },
    {
        data: 'umur',
        mRender: function (_, type, row) {
            var umur = row.umur;
            return umur + ' Hari';
        }
    },
    {   
        data: 'kelamin',
        mRender: function (_, type, row) {
            return row.kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
        }
    },
    {
        data: 'bbl'
    },
    {
        data: 'ibu'
    },
    {
        data: 'ayah'
    },
    {
        mRender: function (_, type, row) {
            var ttl = row.ttl;
            var estimasi = row.estimasi_ttl;

            return ttl + (estimasi == 1 ? '<span class ="badge badge-pill badge-sm bg-info">Estimasi</span>' : '');
        }
    },
    {
        data: 'alamat'
    }
];

if(role == 'admin' || role == 'bidan'){
    configTabel[dtid].splice(1, 0, {
        data: 'nama_pencatat',
        mRender: function(_, t, row){
            var nama_pencatat = row['nama_pencatat'];
            var role = row['role_pencatat'];

            return '<p><small>' + role + '</small> - ' + nama_pencatat + '</p>';
        }
    });
}
