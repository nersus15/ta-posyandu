configTabel["<?= $id ?>"] = [
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

            return ttl + (estimasi ? '<span class ="badge badge-pill badge-sm bg-info">Estimasi</span>' : '');
        }
    },
    {
        data: 'alamat'
    }
];
