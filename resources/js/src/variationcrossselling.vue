<template>
  <div class="widget widget-filter-base" v-if="currentCrossSeller.length > 0">
    <div class="crosslist">
      <template v-if="showheadline === true">
        <div class="h3 mb-3">
          {{  $translate("VariationCrossSelling::Template.translateAddonItemsHeadline") }}
        </div>
      </template>

      <ul class="list-unstyled" id="single-crosslist">
        <li v-for="item in currentCrossSeller" class="mb-1">
          <template v-if="item.data && item.data.variation && item.data.variation.id > 0">
            <label :for="'var_'+item.data.variation.id" class="cursor d-flex align-items-center mb-0">
              <div class="d-flex flex-fill ">
                <template v-if="item.data.images.variation  && item.data.images.variation.length > 0">
                  <div>
                    <img class="img-fluid mx-4 my-2" style="height:70px" :src="item.data.images.variation[0].urlPreview" :alt="item.data.texts.name1">
                  </div>
                </template>
                <template v-else-if="item.data.images.all && item.data.images.all.length > 0">
                  <div>
                    <img class="img-fluid mx-4 my-2" style="height:70px" :src="item.data.images.all[0].urlPreview" :alt="item.data.texts.name1">
                  </div>
                </template>
                <template v-else>
                  <div style="height: 70px;width: 70px" class="mr-3">

                  </div>
                </template>

                <div class="flex-fill my-2">
                  <div class="crosslisttitle">
                    <a :href="item.data | itemURL">
                      <b>{{ item.data.texts.name1  }}</b>
                    </a>
                  </div>
                  <div class="crosslistadditional">
                    <div class="d-flex">
                      <div>
                        <span class="vat small text-muted">
                             {{ item.data.prices.default.price.formatted }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="crossSellerAddToCartBtnWrapper">
                  <button :class="{crossSellerAddToCartBtnRounded : roundedbtns == true}"  :disabled="addItemToBasketLoading" class="btn btn-primary btn-appearance" @click="addItemToBasket([item])">

                    <template v-if="addItemToBasketLoading == true">
                      <i class="fa fa-refresh fa-spin" aria-hidden="true"></i>
                    </template>
                    <template v-else>
                      <i class="fa fa-cart-plus" aria-hidden="true"></i>
                    </template>

                  </button>
                </div>
              </div>
            </label>
          </template>

        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  name: 'variationcrossselling',
  props: {
    showheadline: {
      type: Boolean,
      default: () => false
    },
    roundedbtns: {
      type: Boolean,
      default: () =>false
    },
    crossseller:{
      type:Array,
      default:() => []
    }
  },
  inject: {
    itemId: {
      default: null
    }
  },
  data () {
    return {
      addItemToBasketLoading:false,
      currentCrossSeller:[],
    }
  },
  computed: {
    currentVariation()
    {
      return this.$store.getters[`${this.itemId}/currentItemVariation`]
    },
    //current var
    //watch current var to get crossseller
  },
  created ()
  {
    this.currentCrossSeller = this.crossseller
    console.log("created this.crossseller",this.currentCrossSeller)
  },
  watch:
  {
    currentVariation: {
      handler(currentVariation) {
       this.getCurrentCrossSeller(currentVariation.variation.id)
      },
    }
  },
  methods:
  {
    getCurrentCrossSeller(varId)
    {
      let _self = this;

      $.ajax({
        type: 'get',
        url: '/variationCrossSeller/getCrossSellerForVar/'+varId+'/'+App.language+'/'+1,
        success: function (response)
        {
          if(response.success == true)
          {
            _self.currentCrossSeller = response.vars;
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.log(jqXHR);
        }
      })
    },
    addItemToBasket(items)
    {
      let variations = [];
      let _self = this;

      _self.addItemToBasketLoading = true;

      for(let i = 0 ; i < items.length ; i++)
      {

        let intervalOrderQuantity = items[i].data.variation.intervalOrderQuantity;

        if(intervalOrderQuantity == undefined || intervalOrderQuantity == null)
        {
          intervalOrderQuantity = 1;
        }
        variations.push({
          variationId:items[i].id,
          quantity:intervalOrderQuantity
         })

      }

      let data = {
        "items": variations,
      }

      $.ajax({
        type: 'POST',
        url: '/variationCrossSeller/frontend/addToBasket/',
        data: data,
        success: function (data)
        {
          for( let i = 0; i < data.length; i++)
          {
            if(data[i].error)
            {
              let varName = data[i].error.variationName;

              for(let a = 0 ; a < _self.currentCrossSeller ; a++)
              {
                if(_self.currentCrossSeller[a].data.variation.id == error.placeholder.variationId)
                {
                  varName = _self.currentCrossSeller[a].data.texts.name1
                }
              }

              CeresNotification.error(ceresTranslate("VariationCrossSelling::Template.translateNotEnoughStock",
                  {
                    variationName: varName,
                    stock: data[i].error.placeholder.stock
                  })
              );
            }
            else
            {
              CeresNotification.success(ceresTranslate("VariationCrossSelling::Template.translateAddedToBasketSuccess")).closeAfter(3000);
            }
            if(i === data.length-1)
            {
              vueApp.$store.commit("setBasketItems", data[i].basketItems);
              vueApp.$store.commit("setBasket", data[i].basket);
            }
          }
          _self.addItemToBasketLoading = false;
        },
        error: function (jqXHR, textStatus, errorThrown) {
          _self.addItemToBasketLoading = false;
        }
      })
    }
  }
}
</script>


