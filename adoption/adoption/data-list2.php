<?php
$page_title = '商品列表';
$page_name = 'data-list2';
require __DIR__ . '/parts/__connect_db.php';

?>
<?php include __DIR__ . '/parts/__html_head.php'; ?>
<?php include __DIR__ . '/parts/__navbar.php'; ?>
<div class="container">
    <div class="row">
        <div class="col d-flex justify-content-end">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                </ul>
            </nav>

        </div>
    </div>


    <table class="table table-striped">
        <!-- `pet_id`, `name`, `dog_cat`, `age`, `area`, `address`, `description`, `create_time` -->
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">序號</th>
                <th scope="col">名稱</th>
                <th scope="col">貓咪/狗勾</th>
                <th scope="col">年齡</th>
                <th scope="col">所在地區</th>
                <th scope="col">地址</th>
                <th scope="col">介紹</th>
                <th scope="col">建立時間</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

</div>
<?php include __DIR__ . '/parts/__scripts.php'; ?>
<script>
    const tboday = document.querySelector('tbody');

    let pageData;

    const hashHandler = function(event) {
        let h = parseInt(location.hash.slice(1)) || 1;
        if (h < 1) h = 1;
        console.log(`h: ${h}`);
        getData(h);
    };
    window.addEventListener('hashchange', hashHandler);
    hashHandler(); // 頁面一進來就直接呼叫

    const pageItemTpl = (o) => {

        return `<li class="page-item ${o.active}">
                        <a class="page-link" href="#${o.page}">${o.page}</a>
                </li>`;
    };

    const tableRowTpl = (o) => {
        // `pet_id`, `name`, `dog_cat`, `age`, `area`, `address`, `description`, `create_time`
        return `
        <tr>

            <td></td>
            <td>${o.pet_id}</td>
            <td>${o.name}</td>
            <td>${o.dog_cat}</td>
            <td>${o.age}</td>
            <td>${o.area}</td>
            <td>${o.address}</td>
            <td>${o.description}</td>
            <td>${o.create_time}</td>
        </tr>
        `;
    };

    function getData(page = 1) {
        fetch('data-list2-api.php?page=' + page)
            // .then(r => r.text())
            // .then(obj => console.log(obj))
            .then(r => r.json())
            .then(obj => {
                console.log(obj);
                pageData = obj;
                console.log(obj.rows)
                let str = '';
                for (let i of obj.rows) {
                    str += tableRowTpl(i);
                }
                tboday.innerHTML = str;

                str = '';
                for (let i = obj.page - 3; i <= obj.page + 3; i++) {
                    if (i < 1) continue;
                    if (i > obj.totalPages) continue;
                    const o = {
                        page: i,
                        active: ''
                    }
                    if (obj.page === i) {
                        o.active = 'active';
                    }
                    str += pageItemTpl(o);
                }
                document.querySelector('.pagination').innerHTML = str;
            });
    }
</script>
<?php include __DIR__ . '/parts/__html_foot.php'; ?>