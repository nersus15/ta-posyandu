configTabel["<?= $id ?>"] = [
    { 
        mData: null,
    },
    {
        data: 'nama'
    },
    {
        data: 'alamat'
    },
    {
        mRender: function (_, type, row) {
            var ttl = row.ttl;
            var estimasi = row.estimasi_ttl;

            return ttl + (estimasi ? '<span class ="badge badge-pill badge-sm bg-info">Estimasi</span>' : '');
        }
    },
    {
        data: 'nik'
    }
];
