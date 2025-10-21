  window.OneSignalDeferred = window.OneSignalDeferred || [];
  OneSignalDeferred.push(async function(OneSignal) {
    await OneSignal.init({ appId: "fe361fb5-f7a3-4a47-b210-d74a11802559" });
    OneSignal.Debug.setLogLevel("debug");


     const externalId = `${window.APP.tenancyId}-${window.APP.userId}`;

    try {
      await OneSignal.login(externalId);
      console.log("Usuário logado com external_id:", externalId);

    } catch (e) {      
      if (e.errors?.[0]?.code === "user-2") {
        console.warn("Alias já está em uso, assumindo que já está vinculado.");
        
      } else {
        console.error("Erro ao fazer login:", e);
      }
    }

    const isPushEnabled = await OneSignal.Notification.isPushEnabled();
    if (!isPushEnabled) {
      await OneSignal.Slidedown.promptPush();
    }

    const permission = await OneSignal.Notification.getPermission();
    if (permission === 'denied') {
        OneSignal.PushUnblock.show(); 
    }

  });