configTabel["<?= $id ?>"] = [
    { 
        mData: null,
    },
    {
        data: 'id'
    },
    {
        data: 'nama'
    },
    {
        data: 'kelamain',
        mRender: function (_, type, row) {
            return row.kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
        }
    },
    {
        data: 'alamat'
    },
    {
        data: 'hp'
    },
    {
        data: 'email'
    }
];
