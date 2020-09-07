<?php
$page_title = '商品列表';
$page_name = 'data_list2';
require __DIR__. '/parts/__connect_db.php';

?>
<?php include __DIR__. '/parts/__html_head.php'; ?>
<?php include __DIR__. '/parts/__navbar.php'; ?>
<div class="container">
    <div class="row">
        <div class="col d-flex justify-content-end">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
<!--                    <li class="page-item ">-->
<!--                        <a class="page-link" href="?page=">-->
<!--                            <i class="fas fa-arrow-circle-left"></i>-->
<!--                        </a>-->
<!--                    </li>-->

<!--                    <li class="page-item ">-->
<!--                        <a class="page-link" href="?page="></a>-->
<!--                    </li>-->

<!--                    <li class="page-item ">-->
<!--                        <a class="page-link" href="?page=">-->
<!--                            <i class="fas fa-arrow-circle-right"></i>-->
<!--                        </a>-->
<!--                    </li>-->
                </ul>
            </nav>

        </div>
    </div>


    <table class="table table-striped">
        <!-- `sid`, `name`, `email`, `mobile`, `birthday`, `address`, `created_at` -->
        <thead>
        <tr>
        <th scope="col">#</th>
                <th scope="col">名稱</th>
                <th scope="col">類型</th>
                <th scope="col">品牌</th>
                <th scope="col">定價</th>
                <th scope="col">售價</th>
                <th scope="col">販售數量</th>
                <th scope="col">折扣</th>
                <th scope="col">收藏數</th>
                <th scope="col">建立時間</th>
                <th scope="col">上架狀態</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

</div>
<?php include __DIR__. '/parts/__scripts.php'; ?>
<script>
    const tboday = document.querySelector('tbody');

    let pageData;

    const hashHandler = function(event){
        let h = parseInt(location.hash.slice(1)) || 1;
        if(h<1) h = 1;
        console.log(`h: ${h}`);
        getData(h);
    };
    window.addEventListener('hashchange', hashHandler);
    hashHandler(); // 頁面一進來就直接呼叫

    const pageItemTpl = (o)=>{

        return `<li class="page-item ${o.active}">
                        <a class="page-link" href="#${o.page}">${o.page}</a>
                </li>`;
    };

    const tableRowTpl = (o)=>{

        return `
        <tr>
            <td>${o.sid}</td>
            <td>${o.name}</td>
            <td>${o.type}</td>
            <td>${o.brand}</td>
            <td>${o.pricing}</td>
            <td>${o.price}</td>
            <td>${o.sale}</td>
            <td>${o.discount}</td>
            <td>${o.heart}</td>
            <td>${o.created_at}</td>
            <td>${o.shelf_status}</td>
        </tr>
        `;
    };

    function getData(page=1) {
        fetch('data_list2_api.php?page='+ page)
            .then(r => r.json())
            .then(obj => {
                console.log(obj);
                pageData = obj;
                let str = '';
                for (let i of obj.rows) {
                    str += tableRowTpl(i);
                }
                tboday.innerHTML = str;

                str = '';
                for (let i = obj.page - 3; i <= obj.page + 3; i++) {
                    if (i < 1) continue;
                    if (i > obj.totalPages) continue;
                    const o = {page: i, active: ''}
                    if (obj.page === i) {
                        o.active = 'active';
                    }
                    str += pageItemTpl(o);
                }
                document.querySelector('.pagination').innerHTML = str;
            });
    }
</script>
<?php include __DIR__. '/parts/__html_foot.php'; ?>


