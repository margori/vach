window.paypal
  .Buttons({
    style: {
      shape: 'rect',
      layout: 'vertical',
      color: 'gold',
      label: 'paypal',
    },
    message: {
      amount: 100,
    },
    async createOrder() {
      try {
        const response = await fetch(
          `index.php?r=payment/create-order&referenceCode=${referenceCode}`,
          {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
          }
        );

        const orderData = await response.json();

        if (orderData.id) {
          return orderData.id;
        }
        const errorDetail = orderData?.details?.[0];
        const errorMessage = errorDetail
          ? `${errorDetail.issue} ${errorDetail.description} (${orderData.debug_id})`
          : JSON.stringify(orderData);

        throw new Error(errorMessage);
      } catch (error) {
        console.error(error);
        // resultMessage(`Could not initiate PayPal Checkout...<br><br>${error}`);
      }
    },
    async onApprove(data, actions) {
      try {
        const response = await fetch(
          `index.php?r=payment/capture-order&orderId=${data.orderID}&referenceCode=${referenceCode}`,
          {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
          }
        );

        console.log('capture response', response);

        if (response.status == 200) {
          window.location.replace(
            `index.php?r=payment/response&referenceCode=${referenceCode}&lapTransactionState=APPROVED`
          );
        }
      } catch (error) {
        console.error(error);
        resultMessage(
          `Sorry, your transaction could not be processed...<br><br>${error}`
        );
      }
    },
  })
  .render('#paypal-button-container');
