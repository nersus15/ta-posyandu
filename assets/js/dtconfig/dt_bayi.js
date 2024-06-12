var role = "<?= isset($role) ? $role : '' ?>";
var dtid = "<?= $id ?>";
configTabel[dtid] = [
    { 
        mData: null,
    },
    {
        mRender: function (_, type, row) {
            var nama = row.nama ? row.nama : 'Belum ada nama';
            return nama;
        }
    },
    {
        mRender: function (_, type, row) {
            var umur = row.umur;
            return umur + ' Hari';
        }
    },
    {
        mRender: function (_, type, row) {
            return row.kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
        }
    },
    {
        data: 'bbl'
    },
    {
        data: 'akb'
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

if(role == 'admin')
    configTabel[dtid].splice(1, 0, {data: 'nama_pencatat'});
